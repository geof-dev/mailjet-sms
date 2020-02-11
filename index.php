<?php
    require 'mailjet_sms.php';
    require 'helper.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>SMS via Mailjet</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </head>
    <body class="bg-light">

    <div class="container">
      <div class="py-5 text-center">
        <h2>SMS via Mailjet</h2>
      </div>


<?php
    if(isset($_POST['from']) && ($_POST['from'] != '') &&
    isset($_POST['to']) && ($_POST['to'] != '') &&
    isset($_POST['text']) && ($_POST['text'] != '')
    ){
      if(checkPhoneNumber($_POST['to'])){
        $mailjet = new MailjetSms();
        $response = $mailjet->sendSms($_POST['from'], parseNumber($_POST['to']), $_POST['text']);
        if($response->success()){
          storeSms($response->getData());
          echo '<div class="alert alert-success" role="alert">Le SMS a bien été envoyé !</div>';
        }
        else echo '<div class="alert alert-danger" role="alert">'.$response->getReasonPhrase().'</div>';
      }
      else echo '<div class="alert alert-danger" role="alert">Le format du numéro de téléphone n\'est pas bon !</div>';
    }
    try {
        $db = new PDO('mysql:host=localhost;dbname=test', 'root', '');
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
    
    $req = $db->query('SELECT * FROM sms');
?>



      <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Derniers SMS</span>
          </h4>
          <ul class="list-group mb-3">
          <?php while ($donnees = $req->fetch()) { ?>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0"><?php echo $donnees['from_sms']; ?></h6>
                <small class="text-muted"><?php echo $donnees['text_sms']; ?></small>
              </div>
              <span class="text-muted"><?php echo $donnees['to_sms']; ?></span>
            </li>
            <?php } ?>
          </ul>
        </div>
        <div class="col-md-8 order-md-1">
          <h4 class="mb-3">Envoyer un sms</h4>
          <form  action="" method="post" class="needs-validation">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="to">Numéro de téléphone</label>
                <input type="tel" class="form-control" id="to" name="to" placeholder="" value="" maxlength="10" required>
                <div class="invalid-feedback">
                  Valid first name is required.
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="from">From</label>
                <input type="text" class="form-control" id="from" name="from" placeholder="" value="" required>
                <div class="invalid-feedback">
                  Valid last name is required.
                </div>
              </div>
            </div>

            <div class="mb-3">               
              <label for="text">Texte</label>
              <div class="input-group">
                <textarea class="form-control" id="text" name="text" rows="3"></textarea>
              </div>
            </div>
            <hr class="mb-4">
            <input class="btn btn-primary btn-lg btn-block" type="submit" value="Envoyer le SMS" />
          </form>
        </div>
      </div>

    </body>
</html>