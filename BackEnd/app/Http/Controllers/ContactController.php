<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Mail\Mailer;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = Contact::all();
        return response()->json($messages);
    }

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
    {        $data = $request->validate([
        'first_name' => ['required', 'string', 'min:5'],
        'last_name' => ['required', 'string', 'min:5'],
        'email' => ['required', 'email', 'max:100'],
        'message' => ['required', 'string', 'max:500'],
    ]);

    Contact::create([
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'email' => $data['email'],
        'message' => $data['message'],
    ]);
    return response()->json(['message' => 'message recorded successfully']);//
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        $contact->update([
            'status' => $request->status
        ]);
        return response()->json(['message' => "Status has been updated."]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response()->json(['message' => 'Message deleted successfully']);
    }

    public function sendEmail(Request $request)
    {
        $data = $request->validate([
            'subject' => ['required','string','max:100'],
            'title'   => ['required','string','max:100'],
            'email'    => ['required','email','max:100'],
            'message' => ['required','string','max:1000'],
        ]);

        Mail::to($data['email'])->send(new Mailer($data));
        return response()->json(['message' => 'Email sent successfully']);
    }
}
