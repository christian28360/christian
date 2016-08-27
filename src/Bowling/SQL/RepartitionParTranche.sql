use christian;

select
    concat_ws("/", year(bowl_periode_dt_deb), year(bowl_periode_dt_fin)) as periode,
    sum(case when s.bowl_score_score between 10 and 19 then 1 else 0 end) as "t10",
    sum(case when s.bowl_score_score between 20 and 29 then 1 else 0 end) as "t20",
    sum(case when s.bowl_score_score between 30 and 39 then 1 else 0 end) as "t30",
    sum(case when s.bowl_score_score between 40 and 49 then 1 else 0 end) as "t40",
    sum(case when s.bowl_score_score between 50 and 59 then 1 else 0 end) as "t50",
    sum(case when s.bowl_score_score between 60 and 69 then 1 else 0 end) as "t60",
    sum(case when s.bowl_score_score between 70 and 79 then 1 else 0 end) as "t70",
    sum(case when s.bowl_score_score between 80 and 89 then 1 else 0 end) as "t80",
    sum(case when s.bowl_score_score between 90 and 99 then 1 else 0 end) as "t90",
    sum(case when s.bowl_score_score between 100 and 109 then 1 else 0 end) as "t100",
    sum(case when s.bowl_score_score between 110 and 119 then 1 else 0 end) as "t110",
    sum(case when s.bowl_score_score between 120 and 129 then 1 else 0 end) as "t120",
    sum(case when s.bowl_score_score between 130 and 139 then 1 else 0 end) as "t130",
    sum(case when s.bowl_score_score between 140 and 149 then 1 else 0 end) as "t140",
    sum(case when s.bowl_score_score between 150 and 159 then 1 else 0 end) as "t150",
    sum(case when s.bowl_score_score between 160 and 169 then 1 else 0 end) as "t160",
    sum(case when s.bowl_score_score between 170 and 179 then 1 else 0 end) as "t170",
    sum(case when s.bowl_score_score between 180 and 189 then 1 else 0 end) as "t180",
    sum(case when s.bowl_score_score between 190 and 199 then 1 else 0 end) as "t190",
    sum(case when s.bowl_score_score between 200 and 209 then 1 else 0 end) as "t200",
    sum(case when s.bowl_score_score between 210 and 219 then 1 else 0 end) as "t210",
    sum(case when s.bowl_score_score between 220 and 229 then 1 else 0 end) as "t220",
    sum(case when s.bowl_score_score between 230 and 239 then 1 else 0 end) as "t230",
    sum(case when s.bowl_score_score between 240 and 249 then 1 else 0 end) as "t240",
    sum(case when s.bowl_score_score between 250 and 259 then 1 else 0 end) as "t250",
    sum(case when s.bowl_score_score between 260 and 269 then 1 else 0 end) as "t260",
    sum(case when s.bowl_score_score between 270 and 279 then 1 else 0 end) as "t270",
    sum(case when s.bowl_score_score between 280 and 289 then 1 else 0 end) as "t280",
    sum(case when s.bowl_score_score between 290 and 300 then 1 else 0 end) as "t300"
from 
    bowl_periode p
left join
    bowl_score s on
        s.bowl_score_periode_id = p.bowl_periode_id
group by s.bowl_score_periode_id 
having count(s.bowl_score_periode_id) < 100
order by
    bowl_periode_dt_deb desc,
    bowl_score_score  desc
limit 30

