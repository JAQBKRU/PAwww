-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 06 Lis 2024, 23:40
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
(9, 'services.html', '<h2>Nasze Usługi</h2>\r\n<p>Oferujemy szeroki zakres usług związanych z architekturą i budownictwem. Poniżej znajdują się szczegóły dotyczące naszych najpopularniejszych usług:</p>\r\n<table>\r\n    <tr>\r\n        <th>Usługa</th>\r\n        <th>Opis</th>\r\n    </tr>\r\n    <tr>\r\n        <td>Projektowanie architektoniczne</td>\r\n        <td>Tworzenie nowoczesnych i estetycznych projektów budynków, które spełniają potrzeby funkcjonalne klientów.</td>\r\n    </tr>\r\n    <tr>\r\n        <td>Consulting budowlany</td>\r\n        <td>Profesjonalne doradztwo w zakresie planowania, optymalizacji kosztów i terminów budowy.</td>\r\n    </tr>\r\n    <tr>\r\n        <td>Zarządzanie projektem</td>\r\n        <td>Kompleksowe zarządzanie procesem budowlanym, od koncepcji po realizację projektu.</td>\r\n    </tr>\r\n    <tr>\r\n        <td>Analiza lokalizacji</td>\r\n        <td>Badania i ocena najlepszych lokalizacji dla inwestycji budowlanych, uwzględniając otoczenie i dostępność infrastruktury.</td>\r\n    </tr>\r\n    <tr>\r\n        <td>Rewitalizacja budynków</td>\r\n        <td>Modernizacja i adaptacja istniejących obiektów do współczesnych standardów użytkowych i estetycznych.</td>\r\n    </tr>\r\n</table>\r\n\r\n<p>Skontaktuj się z nami, aby uzyskać więcej informacji na temat naszych usług oraz jak możemy Ci pomóc w Twoim projekcie budowlanym!</p>\r\n\r\n<table>\r\n    <tr>\r\n        <th>Usługa</th>\r\n        <th>Opis</th>\r\n    </tr>\r\n    <tr>\r\n        <td>Nadzór budowlany</td>\r\n        <td>Monitorowanie postępu prac budowlanych, zapewnienie zgodności z projektem oraz normami bezpieczeństwa.</td>\r\n    </tr>\r\n    <tr>\r\n        <td>Konsultacje w zakresie zrównoważonego budownictwa</td>\r\n        <td>Pomoc w projektowaniu i budowie ekologicznych, energooszczędnych budynków z uwzględnieniem nowoczesnych technologii.</td>\r\n    </tr>\r\n    <tr>\r\n        <td>Audyt budowlany</td>\r\n        <td>Ocena jakości wykonania prac budowlanych, analizowanie ryzyk oraz zgodności z regulacjami prawnymi.</td>\r\n    </tr>\r\n    <tr>\r\n        <td>Adaptacja wnętrz</td>\r\n        <td>Przekształcanie istniejących przestrzeni wewnętrznych, dostosowując je do nowych funkcji i estetyki.</td>\r\n    </tr>\r\n    <tr>\r\n        <td>Planowanie infrastruktury</td>\r\n        <td>Kompleksowe planowanie infrastruktury technicznej oraz instalacji wewnętrznych budynku.</td>\r\n    </tr>\r\n</table>', 1);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `page_list`
--
ALTER TABLE `page_list`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `page_list`
--
ALTER TABLE `page_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
