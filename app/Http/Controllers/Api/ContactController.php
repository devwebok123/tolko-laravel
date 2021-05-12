<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Contact\ContactCollection;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * @param  Request  $request
     * @return ContactCollection
     */
    public function getContactName(Request $request)
    {
        $names = Contact::query()
            ->select('name')
            ->where('name', 'LIKE', '%'.$request->input('contacts_name').'%')
            ->orderBy('id', 'asc')
            ->limit(100)
            ->get();

        return new ContactCollection($names);
    }
}
