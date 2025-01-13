-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 13 Sty 2025, 23:23
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
CREATE DATABASE moja_strona;
use moja_strona;
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
(1, 0, 'Komputery i Akcesoria'),
(2, 0, 'Smartfony i Tablety'),
(3, 0, 'Gaming'),
(4, 0, 'Podzespoły komputerowe'),
(5, 0, 'Oprogramowanie'),
(6, 1, 'Laptopy'),
(7, 1, 'Komputery stacjonarne'),
(8, 1, 'Akcesoria komputerowe'),
(9, 1, 'Monitory'),
(10, 1, 'Drukarki i Skanery'),
(11, 3, 'Sprzęt Gamingowy'),
(12, 3, 'Gamingowe Komputery PC'),
(13, 3, 'Gamingowe Laptopy'),
(14, 3, 'Gamingowe Akcesoria'),
(15, 4, 'Karty graficzne'),
(16, 4, 'Procesory'),
(17, 4, 'Płyty główne'),
(18, 4, 'Pamięć RAM'),
(19, 4, 'Dyski SSD i HDD'),
(20, 8, 'Myszki'),
(21, 8, 'Klawiatury'),
(22, 8, 'Słuchawki'),
(23, 8, 'Podkładki pod mysz'),
(24, 11, 'Fotele Gamingowe'),
(25, 11, 'Kontrolery'),
(26, 11, 'Słuchawki Gamingowe'),
(27, 11, 'Podkładki Gamingowe'),
(28, 2, 'Tablety'),
(29, 2, 'Smartfony Android'),
(30, 2, 'Smartfony iOS'),
(31, 5, 'Systemy operacyjne'),
(32, 5, 'Oprogramowanie biurowe'),
(33, 5, 'Oprogramowanie graficzne'),
(34, 5, 'Ochrona antywirusowa'),
(35, 20, 'Myszki przewodowe gamingowe'),
(36, 20, 'Myszki bezprzewodowe gamingowe'),
(37, 21, 'Klawiatury mechaniczne gamingowe'),
(38, 21, 'Klawiatury membranowe gamingowe'),
(39, 23, 'Podkładki z podświetleniem RGB'),
(40, 23, 'Podkładki ergonomiczne'),
(41, 14, 'Myszki gamingowe'),
(42, 14, 'Klawiatury gamingowe'),
(43, 14, 'Słuchawki gamingowe z dźwiękiem przestrzennym'),
(44, 14, 'Kontrolery dla konsol i PC'),
(45, 26, 'Słuchawki gamingowe przewodowe'),
(46, 26, 'Słuchawki gamingowe bezprzewodowe'),
(47, 15, 'Karty graficzne dla graczy'),
(48, 15, 'Karty graficzne dla profesjonalistów'),
(49, 18, 'Pamięć RAM DDR4'),
(50, 18, 'Pamięć RAM DDR5'),
(51, 19, 'Dyski SSD NVMe'),
(52, 19, 'Dyski SSD SATA'),
(53, 19, 'Dyski HDD do magazynowania danych');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `koszyk`
--

CREATE TABLE `koszyk` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `reserved_until` datetime NOT NULL,
  `status` enum('active','expired') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'glowna.html', '<h2>Przykłady Najpopularniejszych Akcesoriów Komputerowych i Smartfonów</h2>\r\n<div class=\"gallery\">\r\n    <div class=\"product\">\r\n        <img src=\"img/mouse.png\" alt=\"Myszka Gamingowa\" />\r\n        <p><b>Myszka Gamingowa</b>, <u>Marka XYZ</u>, <i>przewodowa, RGB</i></p>\r\n    </div>\r\n    <div class=\"product\">\r\n        <img src=\"img/keyboard.png\" alt=\"Klawiatura Mechaniczna\" />\r\n        <p><b>Klawiatura Mechaniczna</b>, <u>Marka ABC</u>, <i>czarna, podświetlana</i></p>\r\n    </div>\r\n    <div class=\"product\">\r\n        <img src=\"img/headset.png\" alt=\"Słuchawki Gamingowe\" />\r\n        <p><b>Słuchawki Gamingowe</b>, <u>Marka XYZ</u>, <i>bezprzewodowe, dźwięk przestrzenny</i></p>\r\n    </div>\r\n    <div class=\"product\">\r\n        <img src=\"img/monitor.png\" alt=\"Monitor 4K\" />\r\n        <p><b>Monitor 4K</b>, <u>Marka DEF</u>, <i>27 cali, 144Hz</i></p>\r\n    </div>\r\n</div>', 1),
(2, 'gallery.html', '<h2>Galeria Akcesoriów i Sprzętu Komputerowego</h2>\r\n<div class=\"gallery\">\r\n    <div class=\"product\">\r\n        <img src=\"img/gallery/image1.png\" alt=\"Obrazek 1\">\r\n        <p>Myszki komputerowe, Akcesoria, Gamingowe, Zestaw gamingowy, Komputer</p>\r\n    </div>\r\n    <div class=\"product\">\r\n        <img src=\"img/gallery/image2.png\" alt=\"Obrazek 2\">\r\n        <p>Klawiatury mechaniczne, Akcesoria komputerowe, Gaming, PC</p>\r\n    </div>\r\n    <div class=\"product\">\r\n        <img src=\"img/gallery/image3.png\" alt=\"Obrazek 3\">\r\n        <p>Fotele gamingowe, Akcesoria, Komfort, Ergonomia, Gaming</p>\r\n    </div>\r\n    <div class=\"product\">\r\n        <img src=\"img/gallery/image4.png\" alt=\"Obrazek 4\">\r\n        <p>Słuchawki gamingowe, Akcesoria, Dźwięk przestrzenny, Gaming</p>\r\n    </div>\r\n    <div class=\"product\">\r\n        <img src=\"img/gallery/image5.png\" alt=\"Obrazek 5\">\r\n        <p>Monitory, Sprzęt komputerowy, HD, Wysoka jakość, Gaming</p>\r\n    </div>\r\n    <div class=\"product\">\r\n        <img src=\"img/gallery/image6.png\" alt=\"Obrazek 6\">\r\n        <p>Karty graficzne, Akcesoria komputerowe, Technologie, Gaming</p>\r\n    </div>\r\n    <div class=\"product\">\r\n        <img src=\"img/gallery/image7.png\" alt=\"Obrazek 7\">\r\n        <p>Procesory, Podzespoły, Komputer, Wydajność, Szybkość</p>\r\n    </div>\r\n    <div class=\"product\">\r\n        <img src=\"img/gallery/image8.png\" alt=\"Obrazek 8\">\r\n        <p>Dyski SSD, Pamięć, Podzespoły komputerowe, Szybkość</p>\r\n    </div>\r\n    <div class=\"product\">\r\n        <img src=\"img/gallery/image9.png\" alt=\"Obrazek 9\">\r\n        <p>Płyty główne, Akcesoria komputerowe, Podzespoły, PC</p>\r\n    </div>\r\n    <div class=\"product\">\r\n        <img src=\"img/gallery/image10.png\" alt=\"Obrazek 11\">\r\n        <p>Laptopy gamingowe, Komputery, Sprzęt komputerowy, Mobilność</p>\r\n    </div>\r\n    <div class=\"product\">\r\n        <img src=\"img/gallery/image11.png\" alt=\"Obrazek 12\">\r\n        <p>Podkładki pod myszki, Akcesoria komputerowe, Gaming, Komfort</p>\r\n    </div>\r\n    <div class=\"product\">\r\n        <img src=\"img/gallery/image12.png\" alt=\"Obrazek 14\">\r\n        <p>Smartfony Android, Urządzenia mobilne, Nowoczesna technologia</p>\r\n    </div>\r\n</div>', 1),
(3, 'filmy.html', '<h2>Filmy o Komputerach i Nowoczesnej Technologii</h2>\r\n<p>Oto kilka filmów, które prezentują fascynujące osiągnięcia w dziedzinie komputerów, sztucznej inteligencji oraz innowacyjnych rozwiązań technologicznych:</p>\r\n\r\n<div class=\"film-container\">\r\n    <h3>1. The Future of Computers: Quantum Computing</h3>\r\n    <iframe width=\"420\" height=\"236\" src=\"https://www.youtube.com/embed/JhHMJCUmq28?si=Y7tEhV62I1cQfqLY\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>\r\n</div>\r\n\r\n<div class=\"film-container\">\r\n    <h3>2. Artificial Intelligence: The Next Frontier</h3>\r\n    <iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/2ePf9rue1Ao\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>\r\n</div>\r\n\r\n<div class=\"film-container\">\r\n    <h3>3. How Computers Work: A Deep Dive into Modern Technology</h3>\r\n    <iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/IlU-zDU6aQ0\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>\r\n</div>\r\n', 1),
(4, 'contact.html', '<h2>Kontakt</h2>\r\n<p>Masz pytania dotyczące sprzętu komputerowego, akcesoriów gamingowych lub naszej działalności? Chcesz dowiedzieć się więcej o produktach, które oferujemy, lub nawiązać współpracę? Jesteśmy tutaj, aby pomóc! Wypełnij poniższy formularz lub skontaktuj się z nami bezpośrednio za pomocą podanych danych kontaktowych. Odpowiemy na Twoje pytania jak najszybciej!</p>\r\n\r\n<section id=\"contact-info\">\r\n  <h3>Informacje Kontaktowe</h3>\r\n  <p><strong>Email:</strong> kontakt@sprzetgamingowy.pl</p>\r\n  <p><strong>Telefon:</strong> +48 987 654 321</p>\r\n  <p><strong>Adres:</strong> ul. Gamingowa 8, 01-234 Warszawa</p>\r\n</section>\r\n\r\n<form action=\"mailto:kontakt@sprzetgamingowy.pl\" method=\"post\" enctype=\"text/plain\">\r\n  <label for=\"name\">Twoje imię:</label><br>\r\n  <input type=\"text\" id=\"name\" name=\"name\" required><br>\r\n  \r\n  <label for=\"email\">Twój email:</label><br>\r\n  <input type=\"email\" id=\"email\" name=\"email\" required><br>\r\n  \r\n  <label for=\"message\">Wiadomość:</label><br>\r\n  <textarea id=\"message\" name=\"message\" required></textarea><br>\r\n  \r\n  <input type=\"submit\" value=\"Wyślij\">\r\n</form>', 1),
(5, 'about.html', '<section>\r\n    <h2>Nasza Misja</h2>\r\n    <p>\r\n        Naszym celem jest dostarczanie rzetelnych i aktualnych informacji na temat najnowszych osiągnięć w dziedzinie technologii komputerowych \r\n        i sprzętu gamingowego. Od wydajnych akcesoriów komputerowych po nowoczesne rozwiązania dla graczy – chcemy inspirować zarówno \r\n        pasjonatów technologii, jak i profesjonalistów w branży IT i gamingowej. Wierzymy, że edukacja w zakresie najnowszych urządzeń \r\n        oraz innowacyjnych technologii może pomóc w stworzeniu lepszych środowisk pracy i zabawy.\r\n    </p>\r\n</section>\r\n\r\n<section>\r\n    <h2>Nasza Historia</h2>\r\n    <p>\r\n        Nasza strona powstała z zamiłowania do nowoczesnych technologii komputerowych i sprzętu gamingowego. Zespół, który stoi za tą inicjatywą, \r\n        składa się z ekspertów i pasjonatów technologii, którzy śledzą najnowsze trendy w dziedzinie IT. Nasze doświadczenie oraz pasja do innowacyjnych \r\n        rozwiązań pozwala nam dzielić się z Wami najnowszymi informacjami i analizami dotyczącymi sprzętu komputerowego i gamingowego.\r\n    </p>\r\n</section>\r\n\r\n<section>\r\n    <h2>Dlaczego warto nas śledzić?</h2>\r\n    <ul>\r\n        <li>Rzetelne informacje na temat najnowszych akcesoriów komputerowych i urządzeń gamingowych.</li>\r\n        <li>Aktualizacje o nowinkach technologicznych, sprzęcie komputerowym i grach.</li>\r\n        <li>Unikalne galerie produktów przedstawiające nowoczesne akcesoria i sprzęt do gier.</li>\r\n        <li>Porady dotyczące wyboru sprzętu komputerowego i gamingowego, a także analizy rynku.</li>\r\n    </ul>\r\n</section>\r\n', 1),
(6, '404.html', '<div>\r\n    <h1>404 - Strona Nie Znaleziona</h1>\r\n    <p>Przepraszamy, ale strona, której szukasz, nie istnieje.</p>\r\n    <p>Możesz wrócić na <a href=\"index.php?idp=glowna\">stronę główną</a> lub sprawdzić inne sekcje.</p>\r\n</div>', 1),
(7, 'jquery.html', '<div id=\"animacjaTestowa1\" class=\"test-block\">Kliknij, a się powiększę</div>\r\n<div id=\"animacjaTestowa2\" class=\"test-block\">Najedź kursorem, a się powiększę</div>\r\n<div id=\"animacjaTestowa3\" class=\"test-block\">Klikaj, abym urósł</div>\r\n\r\n<script src=\"https://code.jquery.com/jquery-3.6.0.min.js\"></script>\r\n\r\n<style>\r\n    .test-block {\r\n        width: 80%;\r\n        max-width: 400px;\r\n        height: 50px;\r\n        margin: 20px auto;\r\n        text-align: center;\r\n        line-height: 50px;\r\n        background-color: #4CAF50;\r\n        color: white;\r\n        font-size: 1.2em;\r\n        border: 2px solid #388E3C;\r\n        border-radius: 5px;\r\n        cursor: pointer;\r\n        transition: all 0.3s ease;\r\n    }\r\n\r\n    .test-block:hover {\r\n        background-color: #45a049;\r\n    }\r\n\r\n    @media (max-width: 600px) {\r\n        .test-block {\r\n            font-size: 1em;\r\n        }\r\n    }\r\n\r\n    @media (max-width: 400px) {\r\n        .test-block {\r\n            font-size: 0.9em;\r\n            height: 35px;\r\n        }\r\n    }\r\n</style>\r\n\r\n<script>\r\n    $(\"#animacjaTestowa1\").on(\"click\", function() {\r\n        var isEnlarged = $(this).data(\"enlarged\");\r\n        \r\n        if (isEnlarged) {\r\n            $(this).animate({\r\n                width: \"80%\",\r\n                fontSize: \"1.2em\",\r\n                borderWidth: \"2px\",\r\n                opacity: 1\r\n            }, 1000).css(\"background-color\", \"#4CAF50\");\r\n            $(this).data(\"enlarged\", false);\r\n        } else {\r\n            $(this).animate({\r\n                width: \"90%\",\r\n                fontSize: \"2.5em\",\r\n                borderWidth: \"5px\",\r\n                opacity: 0.7\r\n            }, 1000).css(\"background-color\", \"#FF9800\");\r\n            $(this).data(\"enlarged\", true);\r\n        }\r\n    });\r\n\r\n    $(\"#animacjaTestowa2\").on(\"mouseover\", function() {\r\n        $(this).animate({\r\n            width: \"90%\",\r\n            fontSize: \"1.5em\"\r\n        }, 500);\r\n    }).on(\"mouseout\", function() {\r\n        $(this).animate({\r\n            width: \"80%\",\r\n            fontSize: \"1.2em\"\r\n        }, 500);\r\n    });\r\n\r\n    $(\"#animacjaTestowa3\").on(\"click\", function() {\r\n        var isEnlarged = $(this).data(\"enlarged\");\r\n        \r\n        if (isEnlarged) {\r\n            $(this).animate({\r\n                width: \"80%\",\r\n                height: \"50px\",\r\n                opacity: 1\r\n            }, 1000).css(\"background-color\", \"#4CAF50\");\r\n            $(this).data(\"enlarged\", false);\r\n        } else {\r\n            $(this).animate({\r\n                width: \"90%\",\r\n                height: \"70px\",\r\n                opacity: 0.8\r\n            }, 1000).css(\"background-color\", \"#2196F3\");\r\n            $(this).data(\"enlarged\", true);\r\n        }\r\n    });\r\n</script>\r\n', 1),
(8, 'js.html', '<div id=\"javascript_form\">\r\n  <form method=\"post\" name=\"background\">\r\n      <input type=\"button\" value=\"Żółty\" onclick=\"changeBackground(\'#FFFF00\')\" class=\"color-button\">\r\n      <input type=\"button\" value=\"Czarny\" onclick=\"changeBackground(\'#000000\')\" class=\"color-button\">\r\n      <input type=\"button\" value=\"Biały\" onclick=\"changeBackground(\'#FFFFFF\')\" class=\"color-button\">\r\n      <input type=\"button\" value=\"Zielony\" onclick=\"changeBackground(\'#00FF00\')\" class=\"color-button\">\r\n      <input type=\"button\" value=\"Niebieski\" onclick=\"changeBackground(\'#0000FF\')\" class=\"color-button\">\r\n      <input type=\"button\" value=\"Pomarańczowy\" onclick=\"changeBackground(\'#FF8000\')\" class=\"color-button\">\r\n      <input type=\"button\" value=\"Szary\" onclick=\"changeBackground(\'#C0C0C0\')\" class=\"color-button\">\r\n      <input type=\"button\" value=\"Czerwony\" onclick=\"changeBackground(\'#FF0000\')\" class=\"color-button\">\r\n  </form>\r\n  \r\n  <div id=\"javascript\">\r\n      <div id=\"data\">Zmień kolor, klikając na guzik</div>\r\n      <div id=\"zegarek\"></div>\r\n  </div>\r\n</div>\r\n\r\n<style>\r\n  .color-button {\r\n    padding: 10px 20px;\r\n    margin: 5px;\r\n    font-size: 16px;\r\n    cursor: pointer;\r\n    border: none;\r\n    border-radius: 5px;\r\n    transition: background-color 0.3s ease, transform 0.2s ease;\r\n  }\r\n\r\n  .color-button:hover {\r\n    transform: scale(1.02);\r\n    background-color: #4caf50;\r\n  }\r\n\r\n  #data {\r\n    font-size: 20px;\r\n    font-weight: bold;\r\n    text-align: center;\r\n    margin-top: 20px;\r\n    border: 1px solid black;\r\n  }\r\n</style>\r\n\r\n<script>\r\n  function changeBackground(color) {\r\n    document.body.style.backgroundColor = color;\r\n  }\r\n</script>\r\n', 1),
(9, 'services.html', '<h2>Nasze Usługi</h2>\r\n<p>Oferujemy szeroki zakres usług związanych z technologią komputerową i sprzętem gamingowym. Poniżej znajdują się szczegóły dotyczące naszych najpopularniejszych usług:</p>\r\n<table>\r\n    <tr>\r\n        <th>Usługa</th>\r\n        <th>Opis</th>\r\n    </tr>\r\n    <tr>\r\n        <td>Projektowanie konfiguracji komputerowych</td>\r\n        <td>Tworzenie zoptymalizowanych konfiguracji komputerowych dostosowanych do potrzeb graczy i profesjonalistów.</td>\r\n    </tr>\r\n    <tr>\r\n        <td>Consulting technologiczny</td>\r\n        <td>Profesjonalne doradztwo w zakresie doboru najnowszych akcesoriów komputerowych i sprzętu gamingowego.</td>\r\n    </tr>\r\n    <tr>\r\n        <td>Instalacja sprzętu</td>\r\n        <td>Kompleksowa instalacja komputerów, podzespołów oraz akcesoriów gamingowych, zapewniająca pełną funkcjonalność.</td>\r\n    </tr>\r\n    <tr>\r\n        <td>Analiza sprzętu</td>\r\n        <td>Ocena wydajności sprzętu komputerowego oraz proponowanie ulepszeń i optymalizacji dla lepszej efektywności.</td>\r\n    </tr>\r\n    <tr>\r\n        <td>Personalizacja akcesoriów</td>\r\n        <td>Customizacja akcesoriów gamingowych, takich jak myszki, klawiatury czy słuchawki, w celu dostosowania do preferencji użytkownika.</td>\r\n    </tr>\r\n</table>\r\n\r\n<p>Skontaktuj się z nami, aby uzyskać więcej informacji na temat naszych usług oraz jak możemy pomóc Ci stworzyć idealne środowisko gamingowe!</p>\r\n\r\n<table>\r\n    <tr>\r\n        <th>Usługa</th>\r\n        <th>Opis</th>\r\n    </tr>\r\n    <tr>\r\n        <td>Nadzór nad sprzętem gamingowym</td>\r\n        <td>Monitorowanie i ocena wydajności sprzętu, aby zapewnić optymalną jakość rozgrywki i pracy.</td>\r\n    </tr>\r\n    <tr>\r\n        <td>Konsultacje w zakresie zrównoważonego budownictwa IT</td>\r\n        <td>Pomoc w projektowaniu i wdrażaniu energooszczędnych i ekologicznych rozwiązań w zakresie technologii komputerowych.</td>\r\n    </tr>\r\n    <tr>\r\n        <td>Audyt komputerowy</td>\r\n        <td>Ocena jakości wykonania i stanu sprzętu komputerowego, identyfikowanie ryzyk i proponowanie rozwiązań na przyszłość.</td>\r\n    </tr>\r\n    <tr>\r\n        <td>Personalizacja systemów komputerowych</td>\r\n        <td>Przekształcanie istniejących konfiguracji komputerowych, dostosowując je do nowych funkcji i wydajności.</td>\r\n    </tr>\r\n    <tr>\r\n        <td>Planowanie infrastruktury IT</td>\r\n        <td>Kompleksowe planowanie infrastruktury IT, instalacji i sieci komputerowych w domach oraz biurach.</td>\r\n    </tr>\r\n</table>', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `produkty`
--

CREATE TABLE `produkty` (
  `id` int(11) NOT NULL,
  `tytul` varchar(255) NOT NULL,
  `opis` text DEFAULT NULL,
  `data_utworzenia` datetime DEFAULT current_timestamp(),
  `data_modyfikacji` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `data_wygasniecia` datetime DEFAULT NULL,
  `cena_netto` decimal(10,2) NOT NULL,
  `podatek_vat` decimal(5,2) NOT NULL,
  `ilosc_magazyn` int(11) NOT NULL,
  `status_dostepnosci` tinyint(1) NOT NULL DEFAULT 1,
  `gabaryt_produktu` varchar(50) DEFAULT NULL,
  `zdjecie` blob DEFAULT NULL,
  `kategoria_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `produkty`
--

INSERT INTO `produkty` (`id`, `tytul`, `opis`, `data_utworzenia`, `data_modyfikacji`, `data_wygasniecia`, `cena_netto`, `podatek_vat`, `ilosc_magazyn`, `status_dostepnosci`, `gabaryt_produktu`, `zdjecie`, `kategoria_id`) VALUES
(1, 'Laptop Pro X', 'Profesjonalny laptop do pracy z zaawansowanymi aplikacjami.', '2025-01-08 22:31:39', '2025-01-13 19:17:13', NULL, '5000.00', '23.00', 15, 1, 'Średni', 0x75706c6f6164732f363738353538613931386162355f696d675f3030312e706e67, 6),
(2, 'Gamingowy Laptop GX', 'Laptop gamingowy z wysokiej jakości kartą graficzną i dużym ekranem.', '2025-01-08 22:31:39', '2025-01-13 23:09:12', NULL, '8500.00', '23.00', 10, 1, 'Duży', 0x75706c6f6164732f363738353538633532646162665f696d675f3030322e706e67, 13),
(3, 'Karta graficzna RTX 4090', 'Najnowocześniejsza karta graficzna dla graczy i profesjonalistów.', '2025-01-08 22:31:39', '2025-01-13 19:17:50', NULL, '9000.00', '23.00', 5, 1, 'Mały', 0x75706c6f6164732f363738353538636533653138665f696d675f3030332e706e67, 15),
(4, 'Płyta główna X570', 'Zaawansowana płyta główna kompatybilna z najnowszymi procesorami AMD.', '2025-01-08 22:31:39', '2025-01-13 19:18:00', NULL, '1200.00', '23.00', 20, 1, 'Średni', 0x75706c6f6164732f363738353538643833663136345f696d675f3030342e706e67, 17),
(5, 'Monitor UltraSharp 4K', 'Profesjonalny monitor 4K z doskonałym odwzorowaniem kolorów.', '2025-01-08 22:31:39', '2025-01-13 19:18:16', NULL, '3200.00', '23.00', 10, 1, 'Duży', 0x75706c6f6164732f363738353538653838373532345f696d675f3030352e706e67, 9),
(6, 'Myszka Gamingowa RGB', 'Ergonomiczna myszka gamingowa z podświetleniem RGB.', '2025-01-08 22:31:39', '2025-01-13 19:19:03', NULL, '150.00', '23.00', 35, 1, 'Mały', 0x75706c6f6164732f363738353539313735663238665f696d675f3030362e706e67, 20),
(7, 'Fotel Gamingowy XComfort', 'Wygodny fotel dla graczy z regulacją pozycji i podświetleniem LED.', '2025-01-08 22:31:39', '2025-01-13 19:19:11', NULL, '950.00', '23.00', 12, 1, 'Duży', 0x75706c6f6164732f363738353539316662643162305f696d675f3030372e706e67, 24),
(8, 'Antywirus XSecure 2025', 'Kompleksowa ochrona przed wirusami i malware.', '2025-01-08 22:31:39', '2025-01-13 19:19:20', NULL, '200.00', '23.00', 50, 1, 'Niematerialny', 0x75706c6f6164732f363738353539323865376231615f696d675f3030382e706e67, 34),
(9, 'Procesor Intel i9-13900K', 'Najlepszy procesor dla graczy i profesjonalistów.', '2025-01-08 22:31:39', '2025-01-13 19:19:30', NULL, '2900.00', '23.00', 30, 1, 'Mały', 0x75706c6f6164732f363738353539333262666435385f696d675f3030392e706e67, 16),
(10, 'Smartfon Android Pro', 'Smartfon z najnowszym systemem Android, doskonały do gier i pracy.', '2025-01-08 22:31:39', '2025-01-13 19:19:38', NULL, '4500.00', '23.00', 25, 1, 'Mały', 0x75706c6f6164732f363738353539336137656633345f696d675f3031302e706e67, 29),
(11, 'Myszka przewodowa Gaming Pro', 'Gamingowa myszka przewodowa z podświetleniem RGB i precyzyjnym sensorem.', '2025-01-13 14:00:00', '2025-01-13 19:21:58', NULL, '120.00', '23.00', 40, 1, 'Mały', 0x75706c6f6164732f363738353539633663323138645f696d675f3031312e706e67, 35),
(12, 'Myszka bezprzewodowa Gaming X', 'Bezprzewodowa myszka gamingowa z możliwością ładowania i długim czasem pracy na baterii.', '2025-01-13 14:00:00', '2025-01-13 19:22:09', NULL, '150.00', '23.00', 30, 1, 'Mały', 0x75706c6f6164732f363738353539643137343637305f696d675f3031322e706e67, 36),
(13, 'Klawiatura mechaniczna RGB Elite', 'Gamingowa klawiatura mechaniczna z programowalnymi klawiszami.', '2025-01-13 14:00:00', '2025-01-13 19:22:21', NULL, '350.00', '23.00', 15, 1, 'Średni', 0x75706c6f6164732f363738353539646430353633635f696d675f3031332e706e67, 37),
(14, 'Podkładka pod mysz RGB Pro', 'Podkładka z podświetleniem RGB i dużą powierzchnią roboczą.', '2025-01-13 14:00:00', '2025-01-13 19:22:31', NULL, '75.00', '23.00', 50, 1, 'Mały', 0x75706c6f6164732f363738353539653737386262325f696d675f3031342e706e67, 39),
(15, 'Pamięć RAM DDR4 16GB', 'Wydajna pamięć RAM DDR4 do komputerów gamingowych i stacji roboczych.', '2025-01-13 14:00:00', '2025-01-13 19:22:40', NULL, '400.00', '23.00', 25, 1, 'Mały', 0x75706c6f6164732f363738353539663064373536625f696d675f3031352e706e67, 49),
(16, 'Dysk SSD NVMe 1TB', 'Szybki dysk SSD NVMe idealny do gier i profesjonalnych zastosowań.', '2025-01-13 14:00:00', '2025-01-13 19:22:51', NULL, '500.00', '23.00', 20, 1, 'Mały', 0x75706c6f6164732f363738353539666261303663305f696d675f3031362e706e67, 51),
(17, 'Słuchawki bezprzewodowe 7.1 ProSound', 'Gamingowe słuchawki bezprzewodowe z dźwiękiem przestrzennym.', '2025-01-13 14:00:00', '2025-01-13 19:23:01', NULL, '600.00', '23.00', 15, 1, 'Średni', 0x75706c6f6164732f363738353561303531386638335f696d675f3031372e706e67, 46),
(18, 'Karta graficzna RTX 4080 Gaming Edition', 'Wysokowydajna karta graficzna z funkcją ray tracing i DLSS 3.', '2025-01-13 14:00:00', '2025-01-13 19:23:15', NULL, '7500.00', '23.00', 8, 1, 'Średni', 0x75706c6f6164732f363738353561313337313836395f696d675f3031382e706e67, 47),
(19, 'Pamięć RAM DDR5 32GB', 'Najnowocześniejsza pamięć RAM DDR5 o wysokiej przepustowości.', '2025-01-13 14:00:00', '2025-01-13 19:23:25', NULL, '800.00', '23.00', 10, 1, 'Mały', 0x75706c6f6164732f363738353561316431653063655f696d675f3031392e706e67, 50),
(20, 'Dysk HDD 4TB', 'Duży dysk HDD idealny do przechowywania multimediów.', '2025-01-13 14:00:00', '2025-01-13 19:23:33', NULL, '350.00', '23.00', 50, 1, 'Duży', 0x75706c6f6164732f363738353561323537333463305f696d675f3032302e706e67, 53);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'user1', 'pass1'),
(2, 'user2', 'pass2'),
(3, 'user3', 'pass3');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `koszyk`
--
ALTER TABLE `koszyk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeksy dla tabeli `page_list`
--
ALTER TABLE `page_list`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `produkty`
--
ALTER TABLE `produkty`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategoria_id` (`kategoria_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT dla tabeli `koszyk`
--
ALTER TABLE `koszyk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `page_list`
--
ALTER TABLE `page_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT dla tabeli `produkty`
--
ALTER TABLE `produkty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `koszyk`
--
ALTER TABLE `koszyk`
  ADD CONSTRAINT `koszyk_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `koszyk_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `produkty` (`id`);

--
-- Ograniczenia dla tabeli `produkty`
--
ALTER TABLE `produkty`
  ADD CONSTRAINT `produkty_ibfk_1` FOREIGN KEY (`kategoria_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
