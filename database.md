# Artikel
| Naam | type | andere |
| --- | --- | --- |
| artikelId |  int | PK
| merkId | Merk.merkId | FK
| naam | string |
| beschrijving | string |
| prijs | double |
| visible | boolean |
| toevoegingsDatum | dateTime  |

# CategorieArtikel
| Naam | type | andere |
| --- | --- | --- |
| categorieId | Categorie.categorieId | FK
| artikelId |  Artikel.artikelId | FK

# Categorie
| Naam | type | andere |
| --- | --- | --- |
| categorieId | int | PK
| naam | string

# Merk
| Naam | type | andere |
| --- | --- | --- |
| merkId | int | PK
| naam | string

# AfbeeldingArtikel
| Naam | type | andere |
| --- | --- | --- |
| artikelId | Artikel.artikelId | FK
| afbeeldingsId | Afbeelding.afbeeldingsId | FK

# Afbeelding
| Naam | type | andere |
| --- | --- | --- |
| afbeeldingsId | int | PK
| werknemersId | Werknemer.werknemersId | FK
| path | string

# Bestelling
| Naam | type | andere |
| --- | --- | --- |
| bestellingId | int | PK
| datum | dateTime
| aankoopAdres | Adres.adresId | FK
| leverAdres | Adres.adresId | FK

# BestellingArtikel
| Naam | type | andere |
| --- | --- | --- |
| bestellingId | Bestelling.bestellingId | FK
| artikelId | Artikel.artikelId | FK
| aantal | int

# KortingArtikel
| Naam | type | andere |
| --- | --- | --- |
| kortingId | int | PK
| artikelId | Artikel.artikelId | FK
| factor | double
| datumStart | dateTime
| datumEind | dateTime

# KortingCategorie
| Naam | type | andere |
| --- | --- | --- |
| kortingId | int | PK
| categorieId | Categorie.categorieId | FK
| factor | double
| datumStart | dateTime
| datumEind | dateTime

# Visited
| Naam | type | andere |
| --- | --- | --- |
| gebruikersId | Gebruiker.gebruikersId | FK
| artikelId | Artikel.artikelId | FK
| datum | dateTime

# Review
| Naam | type | andere |
| --- | --- | --- |
| reviewId | int | PK
| gebruikersId | Gebruiker.gebruikersId | FK
| artikelId | Artikel.artikelId | FK
| datum | dateTime
| beschrijving | string
| sterren | int

# Winkelkar
| Naam | type | andere |
| --- | --- | --- |
| winkelkarId | int | PK 
| gebruikersId | Gebruiker.gebruikersId | FK
| datum | dateTime


# WinkelkarArtikel
| Naam | type | andere |
| --- | --- | --- |
| winkelkarId |Winkelkar.winkelkarId | FK
| artikelId | Artikel.artikelId | FK
| aantal | int

# Gebruiker
| Naam | type | andere |
| --- | --- | --- |
| gebruikersId | int | PK
| wachtwoord | string
| adresId | Adres.adresId | FK
| telefoon | string
| email | string
| voornaam | string
| achternaam | string

# Werknemer
| Naam | type | andere |
| --- | --- | --- |
| werknemersId | int | PK
| wachtwoord | string
| adresId | Adres.adresId | FK
| telefoon | string
| email | string
| voornaam | string
| achternaam | string

# Voorraad
| Naam | type | andere |
| --- | --- | --- |
| voorraadId | int | PK
| artikelId | Artikel.artikelId | FK
| aantal | int
| toevoegingsDatum | dateTime

# Adres
| Naam | type | andere |
| --- | --- | --- |
| adresId | int | PK
| gemeenteId | Gemeente.gemeenteId | FK
| plaats | string

# Gemeente
| Naam | type | andere |
| --- | --- | --- |
| gemeenteId | int | PK
| postcode | string
| naam | string
| provincieId | Provincie.provincieId | FK

# Provincie
| Naam | type | andere |
| --- | --- | --- |
| provincieId | int | PK
| naam | string
| landId | Land.landId | FK

# Land
| Naam | type | andere |
| --- | --- | --- |
| landId | int | PK
| naam | string












