# Feladat 1 - BE
- Az env fileban töltsük ki a szükséges extra paramétereket!
  ```dotenv
  EPUB_CHECKER_JAR="utils/epubcheck-5.1.0/epubcheck.jar"
  JAVA_PATH="/usr/bin/java"
  ```
  
- Első lépésben töltsük le a szükséges epub fileokat a következő paranccsal:
```shell
php artisan app:download-epubs 
```

- Második lépésben gyűjtsük ki az adatokat az epub fileokból a következő paranccsal:
```shell
php artisan app:collect-epubs-metadata
```

- Harmadik lépésben validáljuk a fileokat és írjuk be a validációs eredmények a metadata fileba:
```shell
php artisan app:validate-epubs
```

# Feladat 2 - FE
- `npm install && npm run dev` futtatása
- Oldal betöltése a `/` rooton például: http://publishdrive.test

# Feladat 3 - DB
- Számítsd ki, hogy az egyes években mennyi volt a teljes forgalom forintban mérve (év,
forgalom_HUF).
Az egyes eladások értékét mindig az aktuális dátum szerinti átlagos havi árfolyam alapján kell kiszámolni. Sajnos azonban néha előfordul, hogy egy hónapra nincs meg az árfolyamunk. Ilyenkor – de csak ilyenkor – használhatjuk az adott deviza alapértelmezett árfolyamát.

```sql
SELECT
    YEAR(e.datum) AS ev,
    SUM(
    CASE
    WHEN af.arfolyam IS NOT NULL THEN e.mennyiseg * e.ar / af.arfolyam
    ELSE e.mennyiseg * e.ar / d.alap_arfolyam
    END
    ) AS forgalom_HUF
FROM eladas e
    INNER JOIN deviza d ON e.deviza_id = d.id
    LEFT JOIN arfolyam af ON e.deviza_id = af.deviza_id
    AND YEAR(e.datum) = af.ev
    AND MONTH(e.datum) = af.ho
WHERE d.nev = 'HUF'
GROUP BY YEAR(e.datum)
ORDER BY YEAR(e.datum);
```

- Az előző lekérdezést egészíts ki még egy oszloppal (forgalom_EUR), ami azt is mutatja, hogy a forintban kiszámolt forgalmi értékek EUR-ban mit jelentenek (az EUR árfolyamát is lehetőleg a tranzakció dátum alapján kell megállapítani).

```sql
SELECT
    YEAR(e.datum) AS ev,
    SUM(
    CASE
    WHEN af.arfolyam IS NOT NULL THEN e.mennyiseg * e.ar / af.arfolyam
    ELSE e.mennyiseg * e.ar / d.alap_arfolyam
    END
    ) AS forgalom_HUF,
    ROUND(SUM(
    CASE
    WHEN af.arfolyam IS NOT NULL THEN e.mennyiseg * e.ar / af.arfolyam
    ELSE e.mennyiseg * e.ar / d.alap_arfolyam
    END
    ) / (
    SELECT MAX(arfolyam)
    FROM arfolyam
    WHERE deviza_id = 2
    AND (YEAR(e.datum) = ev OR (YEAR(e.datum) NOT IN (SELECT DISTINCT ev FROM arfolyam)))
    AND MONTH(e.datum) = MONTH(e.datum)
    ), 2) AS forgalom_EUR
FROM eladas e
    INNER JOIN deviza d ON e.deviza_id = d.id
    LEFT JOIN arfolyam af ON e.deviza_id = af.deviza_id
    AND YEAR(e.datum) = af.ev
    AND MONTH(e.datum) = af.ho
WHERE d.nev = 'HUF'
GROUP BY YEAR(e.datum)
ORDER BY YEAR(e.datum);

```
