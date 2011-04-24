#testcase
select to_char(data, 'YYYY') ano, avg(tamanho)
from documento
group by to_char(data, 'YYYY')
order by ano;
