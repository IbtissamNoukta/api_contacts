<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Resources\ContactCollection;
use App\Http\Resources\ContactResource;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $contacts = Contact::all();
        return new ContactCollection($contacts);
    }
    // public function contactToArray($contacts){
    //     foreach($contacts as $contact){
    //         $data['name'] = $contact->name;
    //         $data['tel'] = $contact->tel;
    //     }
    //     return $data;
    // }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'name' => 'required',
            'tel' => 'required'
        ]);
        $contact = Contact::create([
            'name' => $request->name,
            'tel' => $request->tel
        ]);
        return new ContactResource($contact);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        //
        return new ContactResource($contact);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        //
        $this->validate($request, [
            'name' => 'required',
            'tel' => 'required'
        ]);
        $contact->update([
            'name' => $request->name,
            'tel' => $request->tel
        ]);
        return new ContactResource($contact);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        //
        $contact->delete();
        return response()->json([
            'message' => 'contact delete'
        ]);
    }
}
