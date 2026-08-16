<!DOCTYPE html>
<html>
<head>
    <title>Cards</title>
</head>
<body>

@if(session('success'))
    <h3>{{ session('success') }}</h3>
@endif

<a href="{{ route('cards.create') }}">
    Create Card
</a>

<table border="1" cellpadding="10">

<tr>
    <th>ID</th>
    <th>Card Number</th>
    <th>Name</th>
</tr>

@foreach($cards as $card)

<tr>
    <td>{{ $card->id }}</td>
    <td>{{ $card->card_number }}</td>
    <td>{{ $card->name }}</td>
</tr>

@endforeach

</table>

</body>
</html>