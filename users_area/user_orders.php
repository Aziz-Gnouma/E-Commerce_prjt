<?php
// user_orders.php

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['username'])){
    echo "<script>window.open('user_login.php','_self')</script>";
    exit();
}

// Récupérer l'ID de l'utilisateur
$username = $_SESSION['username'];
$get_user = "SELECT * FROM user_table WHERE username='$username'";
$run_user = mysqli_query($con, $get_user);
$row_user = mysqli_fetch_array($run_user);
$user_id = $row_user['user_id'];

// Récupérer les commandes de l'utilisateur
$get_orders = "SELECT * FROM user_orders WHERE user_id='$user_id' ORDER BY order_date DESC";
$run_orders = mysqli_query($con, $get_orders);
$count_orders = mysqli_num_rows($run_orders);
?>

<div class="container">
    <h3 class="text-center text-success mb-5">Mes Commandes</h3>
    
    <?php if($count_orders > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>N°</th>
                        <th>N° Commande</th>
                        <th>Montant</th>
                        <th>Produits</th>
                        <th>Facture</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    while($row_orders = mysqli_fetch_array($run_orders)): 
                        $order_id = $row_orders['order_id'];
                        $amount = $row_orders['amount_due'];
                        $invoice = $row_orders['invoice_number'];
                        $products = $row_orders['total_products'];
                        $date = $row_orders['order_date'];
                        $status = $row_orders['order_status'];
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td>#<?php echo $order_id; ?></td>
                        <td><?php echo $amount; ?> €</td>
                        <td><?php echo $products; ?></td>
                        <td><?php echo $invoice; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($date)); ?></td>
                        <td>
                            <span class="badge bg-<?php echo ($status == 'complete') ? 'success' : 'warning'; ?>">
                                <?php echo ucfirst($status); ?>
                            </span>
                        </td>
                        <td>
                            <?php if($status == 'pending'): ?>
                                <a href="confirm_payment.php?order_id=<?php echo $order_id; ?>" class="btn btn-sm btn-primary">
                                    Confirmer
                                </a>
                            <?php else: ?>
                                <span class="text-success">Confirmé</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php 
                    $i++;
                    endwhile; 
                    ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            <h5>Vous n'avez aucune commande pour le moment.</h5>
            <a href="../products.php" class="btn btn-primary mt-3">Voir nos produits</a>
        </div>
    <?php endif; ?>
</div>