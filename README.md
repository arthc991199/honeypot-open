# honeypot ftp
 
public honeypot https://vvvvvv.com.pl/hp/



### Podsumowanie projektu honeypot


### Opis Projektu Honeypot

Nasz projekt Honeypot jest systemem monitorowania prób ataków na stronę internetową, gromadzącym informacje o potencjalnych atakach w pliku CSV. System działa jako pułapka dla potencjalnych atakujących, rejestrując zarówno próby wejścia na stronę, jak i podjęcie prób logowania przy użyciu różnych metod. Dodatkowo, Honeypot sprawdza login i hasło użytkownika w zewnętrznej bazie danych Have I Been Pwned (HIBP), co pozwala na wykrycie czy podane dane były wcześniej kompromitowane w wyciekach danych.

Projekt został zaprojektowany w taki sposób, aby wszystkie operacje były zapisywane w pliku CSV, co umożliwia późniejszą analizę i ocenę ataków. 

### Struktura Plików

- index.php: Główna strona Honeypot, obsługuje zarówno rejestrację prób ataków, jak i logowanie użytkowników oraz obsługę zapytań POST i GET.
- index.html: Strona wyświetlana, gdy użytkownik nie jest zalogowany. Rejestruje podstawowe wejścia na stronę.
- functions.php: Zawiera funkcje odpowiedzialne za operacje na pliku CSV, takie jak pobieranie i czyszczenie logów, a także obsługę sprawdzenia danych w HIBP i analizę potencjalnych ataków SQLi, XSS i RFI.
- dane.php: Panel administracyjny, gdzie wyświetlane są logi w formie tabeli. Zabezpieczony za pomocą logowania.
- styles.css: Arkusz stylów odpowiedzialny za wygląd strony, w tym poprawę responsywności i optymalizację wyświetlania dużych ilości danych.
- script.js: Obsługuje operacje JavaScript, takie jak dynamiczne ładowanie CSV, paginacja, obsługa odświeżania i wyświetlanie danych w tabeli. 
- attack_patterns.json: Plik zawierający wzorce ataków (np. SQL Injection, XSS, RFI), które są analizowane podczas weryfikacji danych użytkownika. Wzorce mogą być modyfikowane lub rozbudowywane w celu dopasowania do nowych typów ataków.

### Funkcje Systemu Honeypot

1. Rejestracja Wejść i Prób Logowania:
   - Zapisuje każdą próbę wejścia na stronę (nawet bez podania danych).
   - Zapisuje każde logowanie z użyciem formularza.
   
2. Parsowanie Ataków OWASP:
   - Analizuje dane użytkownika pod kątem ataków OWASP (SQLi, XSS, RFI), na podstawie wzorców przechowywanych w pliku `attack_patterns.json`.
   - Wybiera najbardziej zaawansowaną technikę OWASP, jeśli zostanie wykryta.
   
3. Weryfikacja w Have I Been Pwned (HIBP):
   - Sprawdza, czy podane login i hasło użytkownika były kiedykolwiek wyciekiem danych.
   - Wyświetla wynik w logach jako: "Login z wycieku", "Hasło z wycieku" lub "Bezpieczne dane".

4. Zapis do CSV:
   - Dane zapisywane są w pliku CSV z podziałem na kolumny: Timestamp, IP, Request Method, Username, Password, User Agent, Fingerprint, Referrer, Accept-Language, OWASP Techniques, HIBP Username Status, HIBP Password Status.
   
5. Wyświetlanie Logów w Panelu Administracyjnym:
   - Logi można przeglądać na stronie `dane.php`, która oferuje paginację i dynamiczne ładowanie rekordów.
   - Możliwość pobrania logów w formacie CSV oraz ich wyczyszczenie.

6. Zabezpieczenie Logów:
   - Logi oraz dostęp do panelu administracyjnego są zabezpieczone przed nieautoryzowanym dostępem, a użytkownicy są automatycznie przekierowywani na stronę logowania, jeśli nie są zalogowani.

### Instrukcja Użycia Honeypot

1. Wejście na Stronę:
   - Użytkownik wchodzący na stronę, nawet bez podania danych logowania, zostanie zarejestrowany w logach Honeypot.

2. Logowanie i Próby Ataków:
   - Każda próba logowania jest zapisywana, a dane są analizowane pod kątem ataków OWASP i weryfikowane w HIBP.
   - Administrator może przeglądać logi prób ataków i danych logowania w panelu `dane.php`.

3. Dostęp do Logów:
   - Logi można pobrać w formie pliku CSV z panelu administracyjnego, gdzie możliwa jest również ich analiza i czyszczenie.

4. Paginacja i Statystyki:
   - Strona `dane.php` umożliwia przeglądanie logów za pomocą paginacji (po 100 rekordów na stronę).
   - Istnieje możliwość rozbudowy o szczegółowe statystyki w górnej części strony.

### Parsowanie w JSON i Granularność Wzorów

Parsowanie wzorców ataków odbywa się przy użyciu pliku `attack_patterns.json`. Każda kategoria ataków, jak SQLi, XSS, czy RFI, posiada własny zbiór wzorców, które można łatwo rozszerzać. W przyszłości można dodać nowe wzorce lub bardziej granularne wzory ataków. System działa dynamicznie, co oznacza, że wszelkie nowe wzorce będą automatycznie analizowane i dodawane do logów bez potrzeby modyfikowania kodu aplikacji.

### Plany Rozwojowe

1. Granularność Parsowania OWASP:
   - Dodanie bardziej zaawansowanego rozpoznawania wzorców ataków.
   - Możliwość dodania dodatkowych kategorii ataków, takich jak XXE, CSRF, SSRF.

2. Wprowadzenie Panelu Statystyk:
   - W panelu `dane.php` dodamy szczegółowe statystyki, takie jak liczba prób ataków, najczęściej atakowane IP, wykresy.

3. Monitorowanie Skanowania Portów i Enumeracji:
   - W przyszłości można rozbudować Honeypot o monitorowanie prób skanowania portów i enumeracji stron.

4. Usprawnienie Warstwy Wizualnej:
   - Optymalizacja przewijania w tabelach z logami, dodanie większej liczby opcji filtrów, np. filtrowanie po typie ataku.

### Zakończenie

Honeypot jest elastycznym i rozbudowywalnym narzędziem do monitorowania prób ataków i gromadzenia danych. 
Dzięki parsowaniu JSON i integracji z HIBP, oferuje precyzyjną analizę i łatwość rozbudowy. 
Istnieje wiele możliwości dalszego rozwoju, które mogą uczynić to narzędzie jeszcze bardziej efektywnym w identyfikacji i raportowaniu zagrożeń.







Wszystkie te pliki współpracują, tworząc płaską, niezależną architekturę opartą na plikach, bez potrzeby korzystania z baz danych czy bardziej złożonych systemów aplikacji.
