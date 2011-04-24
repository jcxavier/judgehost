#testcase
select doi, título
from documento
where emissor=435623121
union
select d.doi, d.título
from documento d, acede a, pessoa p
where d.doi= a.doi and a.utilizador= p.user_id
and p.instituição=435623121;
