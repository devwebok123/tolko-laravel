<?php


namespace App\Services\Models;

use App\Clients\RosReestrClient;
use App\DataObjects\RosReestr\DocumentInfoObject;
use App\Exceptions\EmptyCadastralException;
use App\Exceptions\NotEnoughMoneyException;
use App\Exceptions\PayTransactionErrorException;
use App\Models\Block;
use App\Models\BlockOrder;
use App\Models\Notification;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BlockOrderService
{

    /** @var RosReestrClient $client */
    protected $client;
    /** @var NotificationService $notificationService */
    protected $notificationService;

    public function __construct(RosReestrClient $client, NotificationService $notificationService)
    {
        $this->client = $client;
        $this->notificationService = $notificationService;
    }

    /**
     * @param Block $block
     * @param string $type
     * @return BlockOrder
     * @throws EmptyCadastralException
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function saveOrder(Block $block, string $type): BlockOrder
    {
        if ($order = $this->getOrder($block, $type)) {
            return $order;
        }

        if (!$block->cadastral_number) {
            throw new EmptyCadastralException("Empty cadastral. Block: {$block->id}");
        }

        $orderInfo = $this->client->saveOrder($block->cadastral_number, $type);

        return BlockOrder::query()->create([
            'block_id' => $block->id,
            'user_id' => Auth::user() ? Auth::user()->id : null,
            'type' => $type,
            'status' => $orderInfo->isPaid() ? BlockOrder::STATUS_PAY : BlockOrder::STATUS_NEW,
            'transaction_id' => $orderInfo->getTransactionId(),
            'document_id' => $orderInfo->getDocumentId()
        ]);
    }

    /**
     * @param Block $block
     * @param string $type
     * @return BlockOrder|null
     */
    public function getOrder(Block $block, string $type): ?BlockOrder
    {
        return BlockOrder::whereBlockId($block->id)->whereType($type)->first();
    }

    /**
     * @return Collection|BlockOrder[]
     */
    public function getForPay(): Collection
    {
        return BlockOrder::query()
            ->where('status', BlockOrder::STATUS_NEW)
            ->get();
    }

    /**
     * @return Collection|BlockOrder[]
     */
    public function getForDownload(): Collection
    {
        return BlockOrder::query()
            ->where('status', BlockOrder::STATUS_PAY)
            ->get();
    }

    /**
     * @throws NotEnoughMoneyException
     * @throws PayTransactionErrorException
     */
    public function payOrders(): void
    {
        $orders = $this->getForPay();

        foreach ($orders as $order) {
            $this->payOrder($order);
        }
    }

    /**
     * @param BlockOrder $order
     * @return BlockOrder
     * @throws
     */
    public function payOrder(BlockOrder $order): BlockOrder
    {
        $transaction = $this->client->getTransactionInfo($order->transaction_id);

        if ($transaction->getCost() > $transaction->getBalance()) {
            throw new NotEnoughMoneyException($order, 'Not enough money');
        }
        if ($transaction->isPaid()) {
            $order->status = BlockOrder::STATUS_PAY;
            $order->pay_date = Carbon::now();
            $order->save();
            return $order;
        }

        $payInfo = $this->client->payTransaction($transaction->getId(), $transaction->getPayConfirmCode());

        if (!$payInfo->isPaid()) {
            throw new PayTransactionErrorException($payInfo);
        }

        $order->status = BlockOrder::STATUS_PAY;
        $order->save();

        return $order;
    }

    /**
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function downloadOrders(): void
    {

        $orders = $this->getForDownload();

        foreach ($orders as $order) {
            $this->downloadOrder($order);
        }
    }

    /**
     * @param BlockOrder $order
     * @return BlockOrder
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function downloadOrder(BlockOrder $order): BlockOrder
    {
        $orderInfo = $this->client->getOrderInfo($order->transaction_id);

        if ($orderInfo->getStatus() === DocumentInfoObject::STATUS_ERROR) {
            return $this->processErrorOrderInfo($order);
        }

        if ($orderInfo->getStatus() !== DocumentInfoObject::STATUS_COMPLETED) {
            return $order;
        }

        return $this->processCompleteInfoOrder($order);
    }

    /**
     * @param BlockOrder $order
     * @return BlockOrder
     * @throws GuzzleException
     */
    protected function processCompleteInfoOrder(BlockOrder $order): BlockOrder
    {
        $fileContent = $this->client->downloadOrder($order->document_id);
        $fileName = '/orders/' . md5(uniqid('', true)) . '.pdf';

        Storage::disk('s3')->put($fileName, $fileContent, 'public');
        $this->notificationService->createNotificationAfterDownloadOrder($order->block_id);

        $order->status = BlockOrder::STATUS_DOWNLOAD;
        $order->path = $fileName;
        $order->save();

        return $order;
    }

    /**
     * @param BlockOrder $order
     * @return BlockOrder
     */
    protected function processErrorOrderInfo(BlockOrder $order): BlockOrder
    {
        $order->status = BlockOrder::STATUS_ERROR;
        $order->save();

        return $order;
    }
}
