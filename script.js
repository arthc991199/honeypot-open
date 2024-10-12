let startIndex = 0;
const rowsPerPage = 100;  // Liczba wierszy na stronę

// Funkcja do ładowania i renderowania tabeli z obsługą stron
function loadCSV(page = 1) {
    fetch('functions.php?get_csv')
        .then(response => {
            if (!response.ok) {
                throw new Error('Błąd przy ładowaniu CSV');
            }
            return response.text();
        })
        .then(data => {
            const rows = data.split("\n").map(row => row.split(","));
            const tableBody = document.querySelector("#csvTable tbody");

            // Wyczyść tabelę przed dodaniem nowych wierszy
            tableBody.innerHTML = "";

            // Ustawienia dla paginacji
            const startIndex = (page - 1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;

            // Dodaj nowe wiersze do tabeli
            for (let i = startIndex; i < endIndex && i < rows.length; i++) {
                const row = rows[i];
                if (row.length >= 11) {
                    let [timestamp, ip, requestMethod, username, password, userAgent, fingerprint, referrer, acceptLanguage, owaspTechniques, hibpUsername, hibpPassword] = row.map(cleanValue);

                    let newRow = `<tr>
                        <td>${timestamp}</td>
                        <td>${ip}</td>
                        <td>${requestMethod}</td>
                        <td>${username}</td>
                        <td>${password}</td>
                        <td>${userAgent}</td>
                        <td>${fingerprint}</td>
                        <td>${referrer}</td>
                        <td>${acceptLanguage}</td>
                        <td>${owaspTechniques}</td>
                        <td>${hibpUsername}</td>
                        <td>${hibpPassword}</td>
                    </tr>`;
                    tableBody.insertAdjacentHTML('beforeend', newRow);
                }
            }

            // Aktualizacja strony
            document.getElementById("currentPage").innerText = page;
        })
        .catch(error => console.error('Błąd przy ładowaniu CSV: ', error));
}

// Funkcja do zmiany strony
function changePage(direction) {
    if (direction === 'next') {
        currentPage++;
    } else if (direction === 'prev' && currentPage > 1) {
        currentPage--;
    }
    loadCSV(currentPage);
}

// Funkcja do czyszczenia wartości
function cleanValue(value) {
    return value ? value.replace(/["]+/g, '').trim() : 'ND';
}

// Funkcja do odświeżania danych
function refreshData() {
    loadCSV(); // Po prostu ponownie załaduj dane bez resetu indeksu
}

// Automatyczne ładowanie danych przy starcie
window.onload = function() {
    loadCSV();
};
