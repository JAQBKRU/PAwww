<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora - Lista Produktów</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #4caf50;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
        }

        .container {
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }

        .add-product {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .add-product:hover {
            background-color: #0056b3;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .product-table th,
        .product-table td {
            padding: 15px;
            text-align: left;
        }

        .product-table thead {
            background-color: #4caf50;
            color: white;
        }

        .product-table tbody tr {
            border-bottom: 1px solid #ddd;
        }

        .product-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .product-table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .actions button {
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            color: white;
            transition: background-color 0.3s;
        }

        .actions .edit {
            background-color: #ffc107;
        }

        .actions .edit:hover {
            background-color: #e0a800;
        }

        .actions .delete {
            background-color: #dc3545;
        }

        .actions .delete:hover {
            background-color: #c82333;
        }

        /* Ukryte wiersze */
        .hidden-row {
            display: none;
        }

        .expand-button {
            cursor: pointer;
            color: #007bff;
            font-size: 16px;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header>
    <h1>Panel Administratora - Lista Produktów</h1>
</header>

<div class="container">
    <a href="#" class="add-product">Dodaj Nowy Produkt</a>

    <table class="product-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tytuł</th>
                <th>Kategoria</th>
                <th>Cena Netto</th>
                <th>VAT</th>
                <th>Ilość</th>
                <th>Status</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Produkt 1</td>
                <td>Elektronika</td>
                <td>199.99 zł</td>
                <td>23%</td>
                <td>15</td>
                <td>Dostępny</td>
                <td class="actions">
                    <button class="edit">Edytuj</button>
                    <button class="delete">Usuń</button>
                </td>
            </tr>
            <!-- Ukryty wiersz -->
            <tr class="hidden-row">
                <td colspan="8">
                    <p><strong>Opis:</strong> Szczegółowy opis produktu 1</p>
                    <p><strong>Właściwości:</strong> Wysokiej jakości elektronika, kompatybilna z różnymi systemami.</p>
                </td>
            </tr>

            <tr>
                <td>2</td>
                <td>Produkt 2</td>
                <td>AGD</td>
                <td>349.99 zł</td>
                <td>23%</td>
                <td>0</td>
                <td>Niedostępny</td>
                <td class="actions">
                    <button class="edit">Edytuj</button>
                    <button class="delete">Usuń</button>
                </td>
            </tr>
            <!-- Ukryty wiersz -->
            <tr class="hidden-row">
                <td colspan="8">
                    <p><strong>Opis:</strong> Szczegółowy opis produktu 2</p>
                    <p><strong>Właściwości:</strong> Wysokiej jakości urządzenia AGD, energooszczędne.</p>
                </td>
            </tr>

            <tr>
                <td>3</td>
                <td>Produkt 3</td>
                <td>Ogród</td>
                <td>89.99 zł</td>
                <td>8%</td>
                <td>50</td>
                <td>Dostępny</td>
                <td class="actions">
                    <button class="edit">Edytuj</button>
                    <button class="delete">Usuń</button>
                </td>
            </tr>
            <!-- Ukryty wiersz -->
            <tr class="hidden-row">
                <td colspan="8">
                    <p><strong>Opis:</strong> Szczegółowy opis produktu 3</p>
                    <p><strong>Właściwości:</strong> Narzędzia ogrodowe, łatwe w użyciu i trwałe.</p>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    // Funkcja do rozwinęcia/zwinięcia szczegółów
    const editButtons = document.querySelectorAll('.edit');
    const hiddenRows = document.querySelectorAll('.hidden-row');

    editButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            const row = hiddenRows[index];

            // Przełączamy widoczność ukrytego wiersza
            if (row.style.display === 'none' || row.style.display === '') {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        });
    });

    const deleteButtons = document.querySelectorAll('.delete');

    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            if (confirm('Czy na pewno chcesz usunąć ten produkt?')) {
                alert('Produkt został usunięty.');
            }
        });
    });
</script>

</body>
</html>
