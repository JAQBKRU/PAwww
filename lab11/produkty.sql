-- Tworzenie tabeli produktów
CREATE TABLE produkty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tytul VARCHAR(255) NOT NULL,
    opis TEXT,
    data_utworzenia DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_modyfikacji DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    data_wygasniecia DATETIME,
    cena_netto DECIMAL(10, 2) NOT NULL,
    podatek_vat DECIMAL(5, 2) NOT NULL,
    ilosc_magazyn INT NOT NULL,
    status_dostepnosci BOOLEAN NOT NULL DEFAULT TRUE,
    kategoria VARCHAR(100),
    gabaryt_produktu VARCHAR(50),
    zdjecie BLOB
);

-- Dodanie przykładowych produktów
INSERT INTO produkty (tytul, opis, data_wygasniecia, cena_netto, podatek_vat, ilosc_magazyn, status_dostepnosci, kategoria, gabaryt_produktu, zdjecie)
VALUES
('Laptop XYZ', 'Nowoczesny laptop z ekranem 15 cali.', '2025-12-31', 3500.00, 23.00, 10, TRUE, 'Elektronika', 'Średni', LOAD_FILE('/sciezka/do/laptop.jpg')),
('Telefon ABC', 'Smartfon z dużym wyświetlaczem.', '2024-12-31', 1200.00, 23.00, 5, TRUE, 'Elektronika', 'Mały', LOAD_FILE('/sciezka/do/telefon.jpg')),
('Pralka QWE', 'Pralka automatyczna o pojemności 7kg.', '2026-12-31', 1500.00, 23.00, 2, TRUE, 'AGD', 'Duży', LOAD_FILE('/sciezka/do/pralka.jpg')),
('Zegarek DEF', 'Stylowy zegarek na rękę.', '2023-06-30', 500.00, 23.00, 0, FALSE, 'Akcesoria', 'Mały', LOAD_FILE('/sciezka/do/zegarek.jpg'));

-- Zestaw warunków dostępności produktu
-- Produkt jest dostępny, jeśli:
-- 1. Status dostępności to TRUE
-- 2. Ilość sztuk na magazynie jest większa od 0
-- 3. Data wygaśnięcia nie jest przekroczona
SELECT 
    id, 
    tytul, 
    opis, 
    ilosc_magazyn, 
    status_dostepnosci, 
    CASE 
        WHEN status_dostepnosci = TRUE AND ilosc_magazyn > 0 AND (data_wygasniecia IS NULL OR data_wygasniecia > NOW()) THEN 'Dostępny'
        ELSE 'Niedostępny'
    END AS status_produktu
FROM produkty;