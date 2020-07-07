<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>LVQ</title>
</head>
<body>
    <?php 
    include 'functions.php';

    $temp_iterasi = 0;
    $temp_alpha = 0;
    $temp_decA = 0;
    $temp_minA = 0;
    if(isset($_POST)){
        $temp_iterasi = $_POST['iterasi'];
        $temp_alpha = $_POST['alpha'];
        $temp_decA = $_POST['decAlpha'];
        $temp_minA = $_POST['minAlpha'];
    }

    $iterasi = floatval($temp_iterasi);
    $alpha = floatval($temp_alpha);
    $decA = floatval($temp_decA);
    $minA = floatval($temp_minA);
    
    $sqrt = array(0,0);
    $min = 0;
    $e=0;
    $jumlah = 0;
    $fitur = [];
    $temp = [];
    $query_training = mysqli_query($koneksi, "SELECT * FROM data_training");
    while($temp = mysqli_fetch_array($query_training)){
        array_push($fitur, $temp);
    }
    $data_training = [];
    for($i=0;$i<count($fitur);$i++){
        for($j=0;$j<14;$j++){
            $data_training[$i][$j] = floatval($fitur[$i][$j]);
        }
    }
    $bobot = array(
        array(0.154,0.750,0.000,0.000,1.000,0.045,0.004,0.823,0.031,0.862,0.128,0.644,0.000,0),
        array(0.231,0.750,0.200,0.000,1.000,0.089,0.011,0.839,0.048,0.815,0.231,0.525,1.000,1)
    );
    
?>
    <table border="1">
            <h3><b>Bobot Awal</b></h3>
            <tr>
                <td>No</td>
                <?php for($k=0;$k<13;$k++){ ?>
                <td>W <?= $k+1 ?></td>
                <?php } ?>
                <td>Target</td>
            </tr>
            <?php for($k=0;$k<2;$k++){ ?>
            <tr>
                <td><?= $k+1; ?></td>
                <?php for($l=0;$l<13;$l++){ ?>
                <td> <?= $bobot[$k][$l]; ?></td>
                <?php } ?>
                <td><?= $bobot[$k][13]; ?></td>
            </tr>
            <?php } ?>
        </table>
        <br>
    <table border="1">
        <h3><b>Parameter</b></h3>
        <tr>
            <td>Alpha</td>
            <td>Dec Alpha</td>
            <td>Min Alpha</td>
        </tr>
        <tr>
            <td><?= $alpha; ?></td>
            <td><?= $decA; ?></td>
            <td><?= $minA; ?></td>
        </tr>
    </table>
    <br>
<?php 
    $i = 0;
    // mencari nilai euclidean
    while(($i<$iterasi) && ($alpha > $minA)){//loop iterasi
        for($j=0;$j<count($data_training);$j++){//loop sejumlah data
            $sum = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
            for($k=0;$k<count($bobot);$k++){//loop sejumlah bobot
                for($l=0;$l<13;$l++){//loop perkolom
                    $hitung[$k][$l] = pow($data_training[$j][$l]-$bobot[$k][$l],2);
                    $pangkat[$k][$l] = floatval($hitung[$k][$l]);
                }
                for($l=0;$l<13;$l++){
                    $sum[$k] = $sum[$k] + $pangkat[$k][$l];
                    // hasil akhir euclidean
                    $sqrt[$k] =  sqrt($sum[$k]);
                }
            }
                $min = min($sqrt[0],$sqrt[1]);
            // update bobot
            for($k=0;$k<count($bobot);$k++){
                if($sqrt[$k]!=$min){
                    for($l=0;$l<13;$l++){
                        $bobot[$k][$l] = $bobot[$k][$l];
                    }
                }
                else if($min == $sqrt[$k]){
                    for($l=0;$l<13;$l++){
                        if($min[$k][13]==$data_training[$j][13]){
                            $updateBobot[$k][$l]= number_format($bobot[$k][$l]+($alpha*($data_training[$j][$l]-$bobot[$k][$l])),3);
                            $temp_bobot[$k][$l] = $updateBobot[$k][$l];
                        } else {
                            $updateBobot[$k][$l]= number_format($bobot[$k][$l]-($alpha*($data_training[$j][$l]-$bobot[$k][$l])),3);
                            $temp_bobot[$k][$l] = $updateBobot[$k][$l];
                        }
                        $bobot[$k][$l] = floatval($temp_bobot[$k][$l]);
                    }
                    
                }
            }
        $e=$i+1;
        echo "<br><h2><b>Epoch ke $e</b></h2>"; ?>
        <table border="1">
            <h3><b>Euclidean Distance | Data ke- <?php echo $j+1 ?></b></h3>
            <tr>
            <?php for($k=0;$k<2;$k++){ ?>
                <td> D <?= $k+1; ?></td>
            <?php } ?>
            </tr>
            <tr>
            <?php for($k=0;$k<2;$k++){ ?>
                <td> <?= number_format($sqrt[$k],3); ?></td>
            <?php } ?>
            </tr>
        </table>
        <table border="1">
            <h3><b>Bobot | Data ke- <?php echo $j+1 ?></b></h3>
            <tr>
                <td>No</td>
                <?php for($k=0;$k<13;$k++){ ?>
                <td>W <?= $k+1 ?></td>
                <?php } ?>
                <td>Target</td>
            </tr>
            <?php for($k=0;$k<count($bobot);$k++){ ?>
            <tr>
                <td><?= $k+1; ?></td>
                <?php for($l=0;$l<13;$l++){ ?>
                <td> <?= number_format($bobot[$k][$l],3); ?></td>
                <?php } ?>
                <td><?= $bobot[$k][13]; ?></td>
            </tr>
            <?php } ?>
        </table>
<?php   
        }
        // update alpha
        $alpha=number_format(($alpha*$decA),3);
            echo "
            <br>
            <table border='1'>
                <td>Alpha Baru</td>
            </tr>
            
            <tr>
                <td>$alpha</td>
            </tr>
        </table>
        <br>";
        
    $i++;
    }
    $data_target = [];
    $kelas = 0;
    $sum1 = 0;
    $sum2 = 0;

    $fitur = [];
    $temp = [];
    $target = [];
    $temp_target = 0;
    $query_testing = mysqli_query($koneksi, "SELECT * FROM data_testing");
    while($temp = mysqli_fetch_array($query_testing)){
        array_push($fitur, $temp);

    }
    $data_testing = [];
    for($i=0;$i<count($fitur);$i++){
        for($j=0;$j<=13;$j++){
            $data_testing[$i][$j] = floatval($fitur[$i][$j]);
        }
    }
    for($j=0;$j<count($data_testing);$j++){//loop sejumlah data
        $sum = array(0,0,0,0,0,0,0,0,0);
        for($k=0;$k<count($bobot);$k++){//loop sejumlah bobot
            for($l=0;$l<13;$l++){//loop perkolom
                $hitung[$k][$l] = pow($data_testing[$j][$l]-$bobot[$k][$l],2);
                $pangkat[$k][$l] = floatval($hitung[$k][$l]);
                $temp_target = $data_testing[$j][13];
            }
            for($l=0;$l<13;$l++){
                $sum[$k] = $sum[$k] + $pangkat[$k][$l];
                $sqrt[$k] = sqrt($sum[$k]);
            }
        }
            $min = min($sqrt[0],$sqrt[1]);
            for($k=0;$k<count($bobot);$k++){
                if($min == $sqrt[$k]){
                    $kelas = $k;
                }
            }
            array_push($data_target,$kelas);
		if ($kelas=="0"){
            $sum1++;
		} else{
            $sum2++;
        }
        array_push($target, $temp_target);
    }
    for($i=0;$i<count($data_testing);$i++){
            if($target[$i] == $data_target[$i]){
                $jumlah +=1;
            }
    }
    echo "Cluster 0 sebanyak: $sum1 <br>"; 
    echo "Cluster 1 sebanyak: $sum2 <br>";
    echo "Jumlah: $jumlah";
    
    // mencari akurasi 
    $akurasi = number_format(($jumlah/count($data_testing))*100, 3);
    echo "<br>Akurasi sebanyak:" . $akurasi . "%";
    ?>
</body>

</html>