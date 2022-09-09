<?php

class Book
{


    public function __construct()
    {

    }

    public function bookData(): bool|array
    {
        global $pdo, $request;
        $stmt = $pdo->query('SELECT * FROM 2_book ORDER BY miejscowosc, nazwisko ASC');
        $this->object = $stmt->fetchAll();
        $stmt->closeCursor();
        return $this->object;

    }

    public function bookCoompaniesData(): bool|array
    {
        global $pdo, $request;


        $stmt= $pdo->query("(SELECT * FROM 2_book_company WHERE (miejscowosc LIKE  '%Radom') ORDER BY miejscowosc, RAND()  DESC LIMIT 1155) UNION (SELECT * FROM 2_book_company WHERE (miejscowosc LIKE  'Warszawa') ORDER BY miejscowosc, RAND()  DESC LIMIT 1155)");
        $this->object = $stmt->fetchAll();
        $stmt->closeCursor();

        return $this->object;

    }

}

?>