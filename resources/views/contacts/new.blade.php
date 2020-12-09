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

    <h3>New Contact</h3>


    <form action="/contacts/new" method="POST">
        @csrf

        <div style="margin-bottom:1.5rem; display:flex; align-items:center;">
            <label for="first_name" style="display:inline-block; width:8rem;">First Name</label>
            <input required type="text" name="first_name">
        </div>

        <div style="margin-bottom:1.5rem; display:flex; align-items:center;">
            <label for="last_name" style="display:inline-block; width:8rem;">Last Name</label>
            <input required type="text" name="last_name">
        </div>

        <div style="margin-bottom:1.5rem; display:flex; align-items:center;">
            <label for="email_addresses[0]" style="display:inline-block; width:8rem;">Email 1</label>
            <input required type="email" name="email_addresses[0]" style="margin-right:2rem;">
            <input type="radio" name="primary_email" value="0" checked>is primary
        </div>

        <div style="margin-bottom:1.5rem; display:flex; align-items:center;">
            <label for="email_addresses[1]" style="display:inline-block; width:8rem;">Email 2</label>
            <input required type="email" name="email_addresses[1]" style="margin-right:2rem;">
            <input type="radio" name="primary_email" value="0">is primary
        </div>

        <div style="margin-bottom:1.5rem; display:flex; align-items:center;">
            <label for="phone_numbers[0]" style="display:inline-block; width:8rem;">Phone 1</label>
            <input type="phone" name="phone_numbers[0]" style="margin-right:2rem;">
            <input type="radio" name="primary_phone" value="0" checked>is primary
        </div>

        <div style="margin-bottom:1.5rem; display:flex; align-items:center;">
            <label for="phone_numbers[1]" style="display:inline-block; width:8rem;">Phone 2</label>
            <input type="phone" name="phone_numbers[1]" style="margin-right:2rem;">
            <input type="radio" name="primary_phone" value="0">is primary
        </div>

        <button type="submit">Create contact</button>
    </form>
</body>
</html>
