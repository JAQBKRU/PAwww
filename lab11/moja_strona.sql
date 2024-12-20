-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 05 Gru 2024, 23:02
-- Wersja serwera: 10.4.27-MariaDB
-- Wersja PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `moja_strona`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `matka` int(11) NOT NULL DEFAULT 0,
  `nazwa` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `categories`
--

INSERT INTO `categories` (`id`, `matka`, `nazwa`) VALUES
(1, 0, 'Kategoria A'),
(2, 0, 'Kategoria B'),
(3, 0, 'Kategoria C'),
(4, 1, 'Podkategoria A1'),
(5, 1, 'Podkategoria A2'),
(6, 2, 'Podkategoria B1');

INSERT INTO categories (id, matka, nazwa)
VALUES
-- Główne kategorie
(1, 0, 'Elektronika'),
(2, 0, 'AGD'),
(3, 0, 'Akcesoria'),
(4, 0, 'Książki'),
(5, 0, 'Sport'),
(6, 0, 'Odzież'),
(7, 0, 'Meble'),
(8, 0, 'Kuchnia'),
(9, 0, 'Dom'),
(10, 0, 'Ogród'),

-- Podkategorie Elektronika
(11, 1, 'Laptopy'),
(12, 1, 'Smartfony'),
(13, 1, 'Kamery'),

-- Podkategorie AGD
(14, 2, 'Pralki'),
(15, 2, 'Mikrofalówki'),
(16, 2, 'Suszarki'),
(17, 2, 'Tostery'),
(18, 2, 'Blendery'),

-- Podkategorie Akcesoria
(19, 3, 'Zegarki'),

-- Podkategorie Meble
(20, 7, 'Biurka'),
(21, 7, 'Krzesła'),
(22, 7, 'Szafy'),

-- Podkategorie Dom
(23, 9, 'Oświetlenie'),
(24, 9, 'Tekstylia');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `page_list`
--

CREATE TABLE `page_list` (
  `id` int(11) NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `page_content` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `page_list`
--

INSERT INTO `page_list` (`id`, `page_title`, `page_content`, `status`) VALUES
(1, 'glowna.html', '<h2>Przykłady Największych Budynków</h2>\r\n<div class=\"gallery\">\r\n    <div class=\"building\">\r\n        <img src=\"img/image1.jpg\" alt=\"Burdż Chalifa\" />\r\n        <p><b>Burdż Chalifa</b>, <u>Dubaj</u>, Zjednoczone Emiraty Arabskie, <i>828 m</i></p>\r\n    </div>\r\n    <div class=\"building\">\r\n        <img src=\"img/image2.jpg\" alt=\"Shanghai Tower\" />\r\n        <p><b>Shanghai Tower</b>, <u>Szanghaj</u>, Chiny, <i>632 m</i></p>\r\n    </div>\r\n    <div class=\"building\">\r\n        <img src=\"img/image6.jpg\" alt=\"One World Trade Center\" />\r\n        <p><b>One World Trade Center</b>, <u>Nowy Jork</u>, Stany Zjednoczone, <i>541 m</i></p>\r\n    </div>\r\n    <div class=\"building\">\r\n        <img src=\"img/image4.jpg\" alt=\"Ping An Finance Center\" />\r\n        <p><b>Ping An Finance Center</b>, <u>Shenzhen</u>, Chiny, <i>599 m</i></p>\r\n    </div>\r\n</div>', 1),
(2, 'gallery.html', '<h2>Galeria</h2>\r\n<div class=\"gallery\">\r\n    <div class=\"building\">\r\n        <img src=\"img/image1.jpg\" alt=\"Obrazek 1\">\r\n        <p>Burdż Chalifa, Dubaj, Zjednoczone Emiraty Arabskie</p>\r\n    </div>\r\n    <div class=\"building\">\r\n        <img src=\"img/image2.jpg\" alt=\"Obrazek 2\">\r\n        <p>Shanghai Tower, Szanghaj, Chiny</p>\r\n    </div>\r\n    <div class=\"building\">\r\n        <img src=\"img/image3.jpg\" alt=\"Obrazek 3\">\r\n        <p>Abraj Al Bait Clock Tower, Mekka, Arabia Saudyjska</p>\r\n    </div>\r\n    <div class=\"building\">\r\n        <img src=\"img/image4.jpg\" alt=\"Obrazek 4\">\r\n        <p>Ping An Finance Center, Shenzhen, Chiny</p>\r\n    </div>\r\n    <div class=\"building\">\r\n        <img src=\"img/image5.jpg\" alt=\"Obrazek 5\">\r\n        <p>Lotte World Tower, Dubaj, Zjednoczone Emiraty Arabskie</p>\r\n    </div>\r\n    <div class=\"building\">\r\n        <img src=\"img/image6.jpg\" alt=\"Obrazek 6\">\r\n        <p>One World Trade Center, Nowy Jork, USA</p>\r\n    </div>\r\n    <div class=\"building\">\r\n        <img src=\"img/image7.jpg\" alt=\"Obrazek 7\">\r\n        <p>Guangzhou CTF Finance Centre, Kanton, Chiny</p>\r\n    </div>\r\n    <div class=\"building\">\r\n        <img src=\"img/image8.jpg\" alt=\"Obrazek 8\">\r\n        <p>Tianjin CTF Finance Centre, Tianjin, Chiny</p>\r\n    </div>\r\n    <div class=\"building\">\r\n        <img src=\"img/image9.jpg\" alt=\"Obrazek 9\">\r\n        <p>China Zun, Pekin, Chiny</p>\r\n    </div>\r\n    <div class=\"building\">\r\n        <img src=\"img/image10.jpg\" alt=\"Obrazek 10\">\r\n        <p>The Burj Mohammed Bin Rashid, Dubaj, Zjednoczone Emiraty Arabskie</p>\r\n    </div>\r\n    <div class=\"building\">\r\n        <img src=\"img/image11.jpg\" alt=\"Obrazek 11\">\r\n        <p>Taipei 101, Tajpej, Tajwan</p>\r\n    </div>\r\n    <div class=\"building\">\r\n        <img src=\"img/image12.jpg\" alt=\"Obrazek 12\">\r\n        <p>Shanghai World Financial Center, Szanghaj, Chiny</p>\r\n    </div>\r\n    <div class=\"building\">\r\n        <img src=\"img/image13.jpg\" alt=\"Obrazek 13\">\r\n        <p>International Commerce Centre, Hongkong, Chiny</p>\r\n    </div>\r\n    <div class=\"building\">\r\n        <img src=\"img/image14.jpg\" alt=\"Obrazek 14\">\r\n        <p>Petronas Towers, Kuala Lumpur, Malezja</p>\r\n    </div>\r\n    <div class=\"building\">\r\n        <img src=\"img/image15.jpg\" alt=\"Obrazek 15\">\r\n        <p>Zifeng Tower, Nankin, Chiny</p>\r\n    </div>\r\n</div>', 1),
(3, 'filmy.html', '<h2>Filmy o Architektura i Budownictwie</h2>\r\n<p>Oto kilka filmów, które pokazują niezwykłe budynki i innowacyjne projekty architektoniczne:</p>\r\n\r\n<div class=\"film-container\">\r\n    <h3>1. The Tallest Buildings in the World</h3>\r\n    <iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/J-4wzbEdaLI?si=D7CH9ZYp7NArdxqM\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>\r\n</div>\r\n\r\n<div class=\"film-container\">\r\n    <h3>2. Amazing Architecture: The World\'s Most Innovative Buildings</h3>\r\n    <iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/NmBs4dpbrjo?si=P-f6mSgruKCJv3h8\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>\r\n</div>\r\n\r\n<div class=\"film-container\">\r\n    <h3>3. Modern Skyscrapers: Architectural Marvels</h3>\r\n    <iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/2UGqp8LXVKk?si=zx2niJHivIP_r6c9\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>\r\n</div>\r\n', 1),
(4, 'contact.html', '<h2>Kontakt</h2>\r\n<p>Masz pytania dotyczące największych budynków świata? Chcesz dowiedzieć się więcej o naszej działalności lub nawiązać współpracę? Jesteśmy tutaj, aby pomóc! Wypełnij poniższy formularz lub skontaktuj się z nami bezpośrednio za pomocą podanych danych kontaktowych. Odpowiemy na Twoje pytania jak najszybciej!</p>\r\n<section id=\"contact-info\">\r\n  <h3>Informacje Kontaktowe</h3>\r\n  <p><strong>Email:</strong> kontakt@najwiekszebudynki.pl</p>\r\n  <p><strong>Telefon:</strong> +48 123 456 789</p>\r\n  <p><strong>Adres:</strong> ul. Przykładowa 12, 00-001 Warszawa</p>\r\n</section>\r\n<form action=\"mailto:example@example.com\" method=\"post\" enctype=\"text/plain\">\r\n  <label for=\"name\">Twoje imię:</label><br>\r\n  <input type=\"text\" id=\"name\" name=\"name\" required><br>\r\n  <label for=\"email\">Twój email:</label><br>\r\n  <input type=\"email\" id=\"email\" name=\"email\" required><br>\r\n  <label for=\"message\">Wiadomość:</label><br>\r\n  <textarea id=\"message\" name=\"message\" required></textarea><br>\r\n  <input type=\"submit\" value=\"Wyślij\">\r\n</form>', 1),
(5, 'about.html', '<section>\r\n    <h2>Nasza Misja</h2>\r\n    <p>\r\n        Pragniemy dostarczać rzetelne i aktualne informacje na temat najnowszych osiągnięć w architekturze na skalę globalną. \r\n        Od najwyższych wieżowców po innowacyjne konstrukcje przyszłości – chcemy inspirować zarówno miłośników architektury, \r\n        jak i osoby zawodowo związane z branżą budowlaną. Wierzymy, że edukacja w zakresie nowoczesnych rozwiązań architektonicznych \r\n        i technologii budowlanych może pomóc w lepszym zrozumieniu przyszłości naszych miast.\r\n    </p>\r\n</section>\r\n\r\n<section>\r\n    <h2>Nasza Historia</h2>\r\n    <p>\r\n        Nasza strona powstała z zamiłowania do wielkich projektów budowlanych i architektury. Zespół, który stoi za tą inicjatywą, \r\n        składa się z ekspertów i pasjonatów budownictwa oraz technologii. Nasze doświadczenie w badaniach oraz miłość do sztuki \r\n        konstrukcji pozwala nam dzielić się z Wami najnowszymi wiadomościami oraz analizami dotyczącymi światowych megastruktur.\r\n    </p>\r\n</section>\r\n\r\n<section>\r\n    <h2>Dlaczego warto nas śledzić?</h2>\r\n    <ul>\r\n        <li>Rzetelne informacje na temat najwyższych i najbardziej zaawansowanych technologicznie budynków na świecie.</li>\r\n        <li>Aktualizacje na temat nowych projektów architektonicznych i budowlanych.</li>\r\n        <li>Unikalne galerie zdjęć przedstawiające detale i piękno światowych megastruktur.</li>\r\n        <li>Porady i analizy dotyczące przyszłości urbanistyki i architektury.</li>\r\n    </ul>\r\n</section>\r\n', 1),
(6, '404.html', '<div>\r\n    <h1>404 - Strona Nie Znaleziona</h1>\r\n    <p>Przepraszamy, ale strona, której szukasz, nie istnieje.</p>\r\n    <p>Możesz wrócić na <a href=\"index.php?idp=glowna\">stronę główną</a> lub sprawdzić inne sekcje.</p>\r\n</div>', 1),
(7, 'jquery.html', '<div id=\"animacjaTestowa1\" class=\"test-block\">Kliknij, a się powiększę</div>\r\n<div id=\"animacjaTestowa2\" class=\"test-block\">Najedź kursorem, a się powiększę</div>\r\n<div id=\"animacjaTestowa3\" class=\"test-block\">Klikaj, abym urósł</div>\r\n<script>\r\n    $(\"#animacjaTestowa1\").on(\"click\", function() {\r\n        $(this).animate({\r\n            width: \"500px\",\r\n            opacity: 0.4,\r\n            fontSize: \"3em\",\r\n            borderWidth: \"10px\"\r\n        }, 1500);\r\n    });\r\n\r\n    $(\"#animacjaTestowa2\").on(\"mouseover\", function() {\r\n        $(this).animate({\r\n            width: 300\r\n        }, 800);\r\n    }).on(\"mouseout\", function() {\r\n        $(this).animate({\r\n            width: 200\r\n        }, 800);\r\n    });\r\n\r\n    $(\"#animacjaTestowa3\").on(\"click\", function() {\r\n        if (!$(this).is(\":animated\")) {\r\n            $(this).animate({\r\n                width: \"+=100\",\r\n                height: \"+=10\",\r\n                opacity: \"+=0.1\"\r\n            }, {\r\n                duration: 3000\r\n            });\r\n        }\r\n    });\r\n</script>', 1),
(8, 'js.html', '<div id=\"javascript_form\">\r\n  <form method=\"post\" name=\"background\">\r\n      <input type=\"button\" value=\"żółty\" onclick=\"changeBackground(\'#FFFF00\')\">\r\n      <input type=\"button\" value=\"czarny\" onclick=\"changeBackground(\'#000000\')\">\r\n      <input type=\"button\" value=\"biały\" onclick=\"changeBackground(\'#FFFFFF\')\">\r\n      <input type=\"button\" value=\"zielony\" onclick=\"changeBackground(\'#00FF00\')\">\r\n      <input type=\"button\" value=\"niebieski\" onclick=\"changeBackground(\'#0000FF\')\">\r\n      <input type=\"button\" value=\"pomarańczowy\" onclick=\"changeBackground(\'#FF8000\')\">\r\n      <input type=\"button\" value=\"szary\" onclick=\"changeBackground(\'#C0C0C0\')\">\r\n      <input type=\"button\" value=\"czerwony\" onclick=\"changeBackground(\'#FF0000\')\">\r\n  </form>\r\n  \r\n  <div id=\"javascript\">\r\n      <div id=\"data\">Zmien kolor, klikajac na guzik</div>\r\n      <div id=\"zegarek\"></div>\r\n  </div>\r\n</div>\r\n', 1),
(10, 'xDD', 'x1', 0);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `page_list`
--
ALTER TABLE `page_list`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `page_list`
--
ALTER TABLE `page_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

INSERT INTO produkty (tytul, opis, data_wygasniecia, cena_netto, podatek_vat, ilosc_magazyn, status_dostepnosci, kategoria, gabaryt_produktu, zdjecie)
VALUES
('Laptop XYZ', 'Nowoczesny laptop z ekranem 15 cali.', '2025-12-31', 3500.00, 23.00, 10, TRUE, 11, 'Średni', NULL), -- Laptopy
('Telefon ABC', 'Smartfon z dużym wyświetlaczem.', '2024-12-31', 1200.00, 23.00, 5, TRUE, 12, 'Mały', NULL), -- Smartfony
('Pralka QWE', 'Pralka automatyczna o pojemności 7kg.', '2026-12-31', 1500.00, 23.00, 2, TRUE, 14, 'Duży', NULL), -- Pralki
('Zegarek DEF', 'Stylowy zegarek na rękę.', '2023-06-30', 500.00, 23.00, 0, FALSE, 19, 'Mały', NULL), -- Zegarki
('Telewizor LMN', 'Telewizor 4K z ekranem 55 cali.', '2025-01-01', 2500.00, 23.00, 8, TRUE, 1, 'Duży', NULL), -- Elektronika
('Mikrofalówka TUV', 'Mikrofalówka o mocy 800W.', '2027-03-15', 400.00, 23.00, 15, TRUE, 15, 'Średni', NULL), -- Mikrofalówki
('Książka XYZ', 'Bestsellerowa powieść przygodowa.', '2028-12-31', 50.00, 5.00, 50, TRUE, 4, 'Mały', NULL), -- Książki
('Kamera UVW', 'Kamera sportowa z wodoodporną obudową.', '2024-08-20', 800.00, 23.00, 3, TRUE, 13, 'Mały', NULL), -- Kamery
('Rower GHJ', 'Rower górski z 21 biegami.', '2025-10-10', 2000.00, 23.00, 4, TRUE, 5, 'Duży', NULL), -- Sport
('Kurtka YTR', 'Ciepła kurtka zimowa.', '2026-02-28', 300.00, 23.00, 20, TRUE, 6, 'Średni', NULL), -- Odzież
('Suszarka FRG', 'Suszarka do włosów o mocy 2000W.', '2026-11-30', 150.00, 23.00, 25, TRUE, 16, 'Mały', NULL), -- Suszarki
('Toster HJK', 'Toster na dwie kromki chleba.', '2026-07-01', 120.00, 23.00, 10, TRUE, 17, 'Mały', NULL), -- Tostery
('Biurko PLM', 'Ergonomiczne biurko z regulowaną wysokością.', '2025-09-15', 700.00, 23.00, 6, TRUE, 20, 'Duży', NULL), -- Biurka
('Krzesło ORT', 'Krzesło biurowe z funkcją odchylania.', '2025-07-20', 400.00, 23.00, 8, TRUE, 21, 'Średni', NULL), -- Krzesła
('Szafa IJK', 'Pojemna szafa dwudrzwiowa.', '2025-12-01', 1200.00, 23.00, 2, TRUE, 22, 'Duży', NULL), -- Szafy
('Blender YUI', 'Blender ręczny o mocy 600W.', '2026-03-01', 200.00, 23.00, 12, TRUE, 18, 'Mały', NULL), -- Blendery
('Zestaw naczyń LKP', '12-elementowy zestaw naczyń.', '2026-08-30', 300.00, 23.00, 7, TRUE, 8, 'Średni', NULL), -- Kuchnia
('Koc TYN', 'Ciepły koc polarowy.', '2027-01-01', 100.00, 23.00, 30, TRUE, 24, 'Średni', NULL), -- Tekstylia
('Lampa UHJ', 'Lampka biurkowa LED.', '2026-09-10', 150.00, 23.00, 20, TRUE, 23, 'Mały', NULL), -- Oświetlenie
('Kosiarka WEC', 'Kosiarka spalinowa do trawy.', '2025-04-15', 2500.00, 23.00, 3, TRUE, 10, 'Duży', NULL); -- Ogród
