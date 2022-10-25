<?php
require_once 'db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kafe</title>
    <link rel="shortcut icon" href="./img/icon.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-800 text-gray-200">
    <form action="index.php" method="post" class="mt-4 w-5/6 mx-auto flex flex-col md:w-1/5 md:mx-4 place-items-center">    
        <select name="name" class="bg-zinc-800 p-1 px-2 rounded-xl border-2 border-gray-200 w-full cursor-pointer hover:bg-zinc-700"> 
            <option value="">Vyberte osobu</option>
            <?php
                $sql = "select * from people;";

                if($result = $con -> query($sql)){
                    while($row = $result -> fetch_assoc()){
                        echo "<option value='".$row["ID"]."'>" . $row["name"] . "</option>";
                    }
                }
            ?>
        </select>
        <input type="submit" name="vypis" value="Vybrat" class="cursor-pointer bg-cyan-700 my-2 w-full rounded-xl p-1 px-3 hover:bg-cyan-600">
        <input type="submit" name="mesice" value="Vypsat nápoje po měsících" class="cursor-pointer w-full  bg-cyan-700 rounded-xl p-1 px-3 hover:bg-cyan-600">
        <input type="submit" name="utrata" value="Útrata" class="cursor-pointer bg-cyan-700 my-2 w-full rounded-xl p-1 px-3 hover:bg-cyan-600" >
    </form>
    
    <table class="table-auto mx-auto w-5/6 mt-16 text-l text-center border-collapse md:text-xl md:w-3/4">
        <tr class="bg-zinc-700" id="table">
            <th class="p-2 border-r-2 border-zinc-400">Typ</th>
            <th class="p-2 border-r-2 border-zinc-400">Počet</th> 
            <th class="p-2 ">Jméno</th>
        
        
        <?php
            $where = "";
            if(isset($_POST["name"]) && $_POST["name"]){
                $id = intval($_POST["name"]);
                $where = "where people.ID = $id";
            }

            if(isset($_POST['vypis'])){
                $sql = "select types.typ as napoj, count(drinks.ID) as pocet, people.name from `drinks` join people on drinks.id_people = people.ID
                join types on drinks.id_types = types.ID $where group by types.typ;";
                
                if($result = $con -> query($sql)){
                    echo "</tr>";
                    while($row = $result -> fetch_assoc()){
                        echo "<tr><td class='p-2 border-r-2 border-zinc-400'>". $row["napoj"] ."</td> <td class='p-2 border-r-2 border-zinc-400'>". $row["pocet"] ."</td> <td class='p-2'>". $row["name"]. "</td></tr>";
                    }
                }
            }
            else if(isset($_POST['mesice'])){
                $sql = "select types.typ as napoj, count(drinks.ID) as pocet, people.name, MONTH(drinks.date) as mesic from drinks join people on drinks.id_people = people.ID 
                join types on drinks.id_types = types.ID $where group by types.typ, mesic order by mesic;";

                if($result = $con -> query($sql)){
                    echo "<th class='p-2 border-l-2 border-zinc-400'>Měsíc</th> </tr>";
                    while($row = $result -> fetch_assoc()){
                        echo "<tr><td class='p-2 border-r-2 border-zinc-400'>". $row["napoj"] ."</td> <td class='p-2 border-r-2 border-zinc-400'>". $row["pocet"] ."</td> <td class='p-2 border-r-2 border-zinc-400'>". $row["name"]. "</td><td>". $row['mesic'] ."</td></tr>";
                    }
                }
            }
            else if(isset($_POST['utrata'])){
                $sql = "select types.typ as napoj, count(drinks.ID) as pocet, SUM(types.price) as utrata, people.name from `drinks` join people on drinks.id_people = people.ID join types on drinks.id_types = types.ID $where group by types.typ;";

                if($result = $con -> query($sql)){
                    echo "<th class='p-2 border-l-2 border-zinc-400'>Útrata</th> </tr>";
                    while($row = $result -> fetch_assoc()){
                        echo "<tr><td class='p-2 border-r-2 border-zinc-400'>". $row["napoj"] ."</td> <td class='p-2 border-r-2 border-zinc-400'>". $row["pocet"] ."</td> <td class='p-2 border-r-2 border-zinc-400'>". $row["name"]. "</td><td>". $row['utrata'] ." Kč</td></tr>";
                    }
                }
            }
        ?>
    </table>
</body>
</html>