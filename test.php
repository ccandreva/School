
<?

include "common.php";

for ($i = 8; $i > 0; $i--)
    test($i);
test('K');
test('PK4');
test('PK3');
test('9');

function test($g1)
{
    $y = Grade2Year($g1);
    $g2 = Year2Grade($y,1);

    print "$g1 -> $y -> $g2\n";
}

?>

