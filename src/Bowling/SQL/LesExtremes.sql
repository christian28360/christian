
use christian;


SELECT b0_.bowl_periode_id AS bowl_periode_id0, b0_.bowl_periode_dt_deb AS bowl_periode_dt_deb1, 
b0_.bowl_periode_dt_fin AS bowl_periode_dt_fin2, b0_.bowl_periode_is_active AS bowl_periode_is_active3, 
b0_.bowl_periode_created_on AS bowl_periode_created_on4, 
b0_.bowl_periode_updated_on AS bowl_periode_updated_on5 
FROM bowl_periode b0_ 
WHERE ((b0_.bowl_periode_dt_deb >= '31/12/2015'))
 OR b0_.bowl_periode_dt_deb <= ?) AND b0_.bowl_periode_dt_fin >= ?) OR b0_.bowl_periode_dt_fin <= ?' 