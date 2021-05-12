<?php


namespace App\Clients;

use App\DataObjects\Cian\ComplaintListObject;
use App\DataObjects\Cian\NotificationListObject;
use App\DataObjects\Cian\OrderOfferObject;
use App\DataObjects\Cian\CoverageStatisticObject;
use App\DataObjects\Cian\ViewsByDatesObject;
use App\DataObjects\RosReestr\DocumentInfoObject;
use App\DataObjects\RosReestr\PayTransactionObject;
use App\DataObjects\RosReestr\SaveOrderObject;
use App\DataObjects\RosReestr\TransactionInfoObject;
use App\Models\BlockOrder;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;

class RosReestrClient extends Client
{

    protected const SAVE_ORDER_MODE_BLIND = 'blind';

    protected const BASE_URL = 'https://apirosreestr.ru/';

    protected const URI_SAVE_ORDER = 'api/cadaster/save_order';
    protected const URI_TRANSACTION_INFO = 'api/transaction/info';
    protected const URI_TRANSACTION_PAY = '/api/transaction/pay';
    protected const URI_ORDER_INFO = '/api/cadaster/orders';
    protected const URI_DOWNLOAD_ORDER = 'api/cadaster/download';

    protected const FORMAT_PDF = 'PDF';
    protected const DOCUMENT_ORIENTATION = 'portrait';

    /**
     * @param string $cadastral
     * @param string $type
     * @return SaveOrderObject
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function saveOrder(string $cadastral, string $type): SaveOrderObject
    {
        $payload = [
            'mode' => self::SAVE_ORDER_MODE_BLIND,
            'cadnomer' => $cadastral,
            'documents' => [$type]
        ];
        $response = $this->post(self::BASE_URL . self::URI_SAVE_ORDER, [
            'json' => $payload,
            'headers' => $this->getHeaders(),
        ]);

        $content = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        return SaveOrderObject::createFromArray($content);
    }

    /**
     * @param int $transactionId
     * @return TransactionInfoObject
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function getTransactionInfo(int $transactionId): TransactionInfoObject
    {
        $response = $this->post(self::BASE_URL . self::URI_TRANSACTION_INFO, [
            'json' => [
                'id' => $transactionId
            ],
            'headers' => $this->getHeaders()
        ]);
        $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        return TransactionInfoObject::createFromArray($data);
    }

    /**
     * @param int $transactionId
     * @param string $confirmCode
     * @return PayTransactionObject
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function payTransaction(int $transactionId, string $confirmCode): PayTransactionObject
    {
        $response = $this->post(self::BASE_URL . self::URI_TRANSACTION_PAY, [
            'json' => [
                'id' => $transactionId,
                'confirm' => $confirmCode,
            ],
            'headers' => $this->getHeaders()
        ]);

        $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        return PayTransactionObject::createFromArray($data);
    }

    /**
     * @param int $transactionId
     * @return DocumentInfoObject
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function getOrderInfo(int $transactionId): DocumentInfoObject
    {
        $response = $this->post(self::BASE_URL . self::URI_ORDER_INFO, [
            'json' => [
                'id' => $transactionId
            ],
            'headers' => $this->getHeaders()
        ]);

        $content = $response->getBody()->getContents();
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        return DocumentInfoObject::createFromArray($data);
    }

    /**
     * @param int $documentId
     * @return string
     * @throws GuzzleException
     */
    public function downloadOrder(int $documentId): string
    {
        $response = $this->post(self::BASE_URL . self::URI_DOWNLOAD_ORDER, [
            'json' => [
                'document_id' => $documentId,
                'format' => self::FORMAT_PDF,
                'pdf_orientation' => self::DOCUMENT_ORIENTATION,
            ],
            'headers' => $this->getHeaders(),
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * @return array
     */
    protected function getHeaders(): array
    {
        return [
            'TOKEN' => \config('integrations.rosreestr.access_token'),
        ];
    }
}
