<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tambah Categories</title>
</head>
<body>
    <h1>Tambah Category</h1>

    <form action="/category/store" method="POST">
        @csrf
        <label for="">Nama Category</label><br>
        <input type="text" name="nama_category"><br><br>
        <label for="">Deskripsi Category</label><br>
        <input type="text" name="deskripsi_category"><br><br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>