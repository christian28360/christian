use christian;

SELECT   
        concat(DATE_FORMAT(p.bowl_periode_dt_deb, '%Y'), '/', DATE_FORMAT(p.bowl_periode_dt_fin, '%Y')) AS 'saison',
        concat(ev.bowl_evt_code, ', ', ev.bowl_evt_libelle) AS 'Evenement',
        DATE_FORMAT(j.bowl_jnee_date, '%d/%m/%Y') AS 'jDate',
        j.bowl_jnee_handicap AS 'jHdp',
        sr.bowl_serie_no AS 'srNo',
        DATE_FORMAT(sr.bowl_serie_date, '%d/%m/%Y') AS 'srDate',
        sc.bowl_score_score AS 'score',
        sc.bowl_score_strike AS 'X',
        (sc.bowl_score_spare) AS '/',
        (sc.bowl_score_split) AS 'split',
        sc.bowl_score_gagnee AS 'win',
        sc.bowl_score_comptePasPourMoyenne AS 'Exclus'
FROM     
    bowl_journee j
    LEFT JOIN bowl_serie sr ON sr.bowl_serie_jnee_id = j.bowl_jnee_id
    LEFT JOIN bowl_score sc ON sc.bowl_score_serie_id = sr.bowl_serie_id
    LEFT JOIN bowl_evenement ev ON ev.bowl_evt_id = j.bowl_jnee_evt_id
    LEFT JOIN bowl_periode p ON p.bowl_periode_id = j.bowl_jnee_periode_id
ORDER BY 
    p.bowl_periode_dt_deb DESC,
    j.bowl_jnee_date DESC,
    sr.bowl_serie_no desc

