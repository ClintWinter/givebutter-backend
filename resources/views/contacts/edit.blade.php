<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    @if($errors->any())
        @foreach($errors->all() as $error)
            <div style="color:darkred;">{{ $error }}</div>
        @endforeach
        <div style="height:2rem;"></div>
    @endif

    @if(session('status'))
        <div style="color:green;">{{ session('status') }}</div>
        <div style="height:2rem;"></div>
    @endif

    <h3>Edit Contact</h3>


    <form action="/contacts/{{ $contact->id }}/edit" method="POST">
        @csrf

        @method('PUT')

        <div style="margin-bottom:1.5rem; display:flex; align-items:center;">
            <label for="first_name" style="display:inline-block; width:8rem;">First Name</label>
            <input required type="text" name="first_name" value="{{ $contact->first_name }}">
        </div>

        <div style="margin-bottom:1.5rem; display:flex; align-items:center;">
            <label for="last_name" style="display:inline-block; width:8rem;">Last Name</label>
            <input required type="text" name="last_name" value="{{ $contact->last_name }}">
        </div>

        @foreach ($contact->email_addresses as $key => $email)
            <div id="email{{ $key }}" style="margin-bottom:1.5rem; display:flex; align-items:center;">
                <label for="email_addresses[{{ $key }}]" style="display:inline-block; width:8rem;">Email {{ $key+1 }}</label>

                <input required type="email" name="email_addresses[{{ $key }}]" style="margin-right:2rem;" value="{{ $email['email_address'] }}">

                <span style="margin-right:2rem;">
                    <input type="radio" name="primary_email" value="{{ $key }}" {{ $email['is_primary'] ? 'checked' : '' }}>
                    is primary
                </span>

                <button type="button" onclick="document.querySelector('#email{{ $key }}').remove();">Delete</button>
            </div>
        @endforeach

        @foreach ($contact->phone_numbers as $key => $phone)
            <div style="margin-bottom:1.5rem; display:flex; align-items:center;">
                <label for="phone_numbers[{{ $key }}]" style="display:inline-block; width:8rem;">Phone {{ $key+1 }}</label>
                <input required type="text" name="phone_numbers[{{ $key }}]" style="margin-right:2rem;" value="{{ $phone['phone_number'] }}">
                <input type="radio" name="primary_phone" value="{{ $key }}" {{ $phone['is_primary'] ? 'checked' : '' }}>is primary
            </div>
        @endforeach

        <button type="submit">Update contact</button>
    </form>
</body>
</html>
