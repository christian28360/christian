use christian;

SELECT   
        sr.bowl_serie_id AS 'srID',
        sr.bowl_serie_no AS 'srNo',
        DATE_FORMAT(sr.bowl_serie_date, '%d/%m/%Y') AS 'srDate',
        sc.bowl_score_id AS 'scID',
        sc.bowl_score_score AS 'score',
        sc.bowl_score_strike AS 'X',
        (sc.bowl_score_spare) AS '/',
        (sc.bowl_score_split) AS 'split',
        sc.bowl_score_gagnee AS 'win',
        sc.bowl_score_comptePasPourMoyenne AS 'Exclus'
FROM     
    bowl_serie sr
    LEFT JOIN bowl_score sc ON sc.bowl_score_serie_id = sr.bowl_serie_id

