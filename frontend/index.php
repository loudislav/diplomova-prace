<?php
    session_start();

    $home = "/greybox/registrace/";
    //$home = "/greybox/registrace/test/";
    $url = "debate-greybox.herokuapp.com/api/";
    //$url = "localhost:8000/api/";

    if (isset($_SESSION['last_login']) && (time() - $_SESSION['last_login'] > 86400)) {
        session_unset();
        session_destroy();
    }

    if (isset($_COOKIE["greybox-language"])) {
        $language = $_COOKIE["greybox-language"];
    } else {
        $language = "cz";
    }
    if (isset($_GET["lang"])) {
        setcookie("greybox-language", $_GET["lang"], time()+2629743, $home);
        $language = $_GET["lang"];
    }
    include("languages/$language.php");

    function getApplied($event) {
        $ch = curl_init();

        $urlFinal = $GLOBALS["url"]."event/".$event."/registration?api_token=".$_SESSION['token'];

        curl_setopt($ch, CURLOPT_URL, $urlFinal);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch), true);
        $code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        curl_close($ch);

        return array("response" => $response, "code" => $code);
    }

    function getAppliedBefore() {
        $ch = curl_init();

        $urlFinal = $GLOBALS["url"]."user/".$_SESSION["user_id"]."/registration";

        curl_setopt($ch, CURLOPT_URL, $urlFinal);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch), true);
        $code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        curl_close($ch);

        return array("response" => $response, "code" => $code);
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $lang['description']; ?>">
    <title><?php echo $lang['title']; ?></title>
    
    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-" crossorigin="anonymous">
    
    <!--[if lte IE 8]>
        <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/grids-responsive-old-ie-min.css">
    <![endif]-->
    <!--[if gt IE 8]><!-->
        <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/grids-responsive-min.css">
    <!--<![endif]-->
    
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    
        <!--[if lte IE 8]>
            <link rel="stylesheet" href="css/style-old-ie.css">
        <![endif]-->
        <!--[if gt IE 8]><!-->
            <link rel="stylesheet" href="css/style.css">
        <!--<![endif]-->

    <script type="text/javascript" src="js/functions.js" async></script>
    <script type="text/javascript" src="https://secure.smartform.cz/api/v1/smartform.js" async></script>
    <script type="text/javascript">
        var smartform = smartform || {};
        smartform.beforeInit = function initialize() {
            smartform.setClientId('8ndPcVUJ5B');
        }
    </script>
</head>
<body>


<?php
    $page = $_REQUEST["p"];

    if ($page == "odhlasit") {
        $ch = curl_init();

        $urlFinal = $url."logout";
        $data = array("api_token" => $_SESSION["token"]);

        curl_setopt($ch, CURLOPT_URL, $urlFinal);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = json_decode(curl_exec($ch), true);
        $code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        curl_close($ch);

        if ($code == 200) {
            session_unset();
            session_destroy();
            $page = null;
        }
    }
?>




<header>
    <div class="home-menu pure-menu pure-menu-horizontal pure-menu-fixed">
        <?php
            if (isset($_SESSION["token"])) {
        ?>
        <a class="pure-menu-heading" href="?p=prihlaska">greybox</a>

        <ul class="pure-menu-list">
            <li class="pure-menu-item"><a href="?p=prihlaska" class="pure-menu-link"><?php echo $lang['home']; ?></a></li>
            <li class="pure-menu-item"><a href="?p=seznam" class="pure-menu-link"><?php echo $lang['list']; ?></a></li>
            <li class="pure-menu-item"><?php echo $lang['logged_in'] . ': ' . $_SESSION["email"]; ?></li>
            <li class="pure-menu-item"><a href="?p=odhlasit" class="pure-menu-link"><?php echo $lang['logout']; ?></a></li>
            <li class="pure-menu-item">
                <?php
                if ($language != "cz") {
                    echo "<a href=\"$home?p=$page&lang=cz\" class=\"pure-menu-link\">cz</a>";
                } else {
                    echo "cz";
                }
                ?>
            </li>
            <li class="pure-menu-item">
                <?php
                if ($language != "en") {
                    echo "<a href=\"$home?p=$page&lang=en\" class=\"pure-menu-link\">en</a>";
                } else {
                    echo "en";
                }
                ?>
            </li>
        </ul>
        <?php
            } else {
        ?>
        <a class="pure-menu-heading" href="<?php echo $home; ?>">greybox</a>

        <ul class="pure-menu-list">
            <li class="pure-menu-item"><a href="<?php echo $home; ?>" class="pure-menu-link"><?php echo $lang['home']; ?></a></li>
            <li class="pure-menu-item"><a href="?p=prihlaseni" class="pure-menu-link"><?php echo $lang['login']; ?></a></li>
            <li class="pure-menu-item">
                <?php
                if ($language != "cz") {
                    echo "<a href=\"$home?p=$page&lang=cz\" class=\"pure-menu-link\">cz</a>";
                } else {
                    echo "cz";
                }
                ?>
            </li>
            <li class="pure-menu-item">
                <?php
                if ($language != "en") {
                    echo "<a href=\"$home?p=$page&lang=en\" class=\"pure-menu-link\">en</a>";
                } else {
                    echo "en";
                }
                ?>
            </li>
        </ul>
        <?php   
            }
        ?>
    </div>
</header>

<?php
    if (empty($page)) {
        if (isset($_SESSION["token"])) {
            echo "<script> window.location.replace('$home.?p=prihlaska'); </script>";
        }
?>
<div class="splash-container">
    <div class="splash">
        <h1 class="splash-head"><?php echo $lang['title']; ?></h1>
        <p class="splash-subhead">
            <?php echo $lang['login_to_continue']; ?>
        </p>
        <p>
            <a href="?p=registrace" class="pure-button pure-button-primary"><?php echo $lang['sign_up']; ?></a>
            <a href="?p=prihlaseni" class="pure-button pure-button-primary"><?php echo $lang['login']; ?></a>
        </p>
    </div>
</div>
<?php
    }
?>

<?php
    if ($page == "prijata") {
        if (!isset($_SESSION["token"])) {
            echo "<script> window.location.replace('$home'); </script>";
        }
?>
<div class="splash-container">
    <div class="splash">
        <h1 class="splash-head"><?php echo $lang['application_accepted']; ?></h1>
        <p class="splash-subhead">
            <?php echo $lang['apply_or_logout']; ?>
        </p>
        <p>
            <a href="?p=prihlaska" class="pure-button pure-button-primary"><?php echo $lang['apply_another']; ?></a>
            <a href="?p=odhlasit" class="pure-button pure-button-primary"><?php echo $lang['system_logout']; ?></a>
        </p>
    </div>
</div>
<?php
    }
?>

<div class="content-wrapper">

    <?php
        if ($page == "prihlaseni") {
    ?>
    <div class="content">
        <h2 class="content-head is-center"><?php echo $lang['login']; ?></h2>

        <div class="pure-g">
            <div class="pure-u-1 is-center">
                <p><?php echo $lang['missing_account']; ?> <a href="?p=registrace"><?php echo $lang['please_sign_up']; ?></a>. <?php echo $lang['original_credentials']; ?></p>
                <form class="pure-form pure-form-aligned" method="post">
                    <fieldset>
                        <div class="pure-control-group">
                            <label for="email"><?php echo $lang['email']; ?></label>
                            <input id="email" type="email" name="email" required>
                        </div>
                        <div class="pure-control-group">
                            <label for="password"><?php echo $lang['password']; ?></label>
                            <input id="password" type="password" name="password" required>
                        </div>
                        <input type="hidden" name="action" value="login">

                        <div class="pure-controls">
                            <button type="submit" class="pure-button"><?php echo $lang['login!']; ?></button>
                        </div>
                    </fieldset>
                </form>
                <!--<p><a href="?p=zapomenute"><?php echo $lang['forgotten_password']; ?></a></p>-->
            </div>
        </div>

    </div>
    <?php
        }
    ?>


    <?php
        if ($page == "registrace") {
    ?>
    <div class="content">
        <h2 class="content-head is-center"><?php echo $lang['sign_up']; ?></h2>

        <div class="pure-g">
            <div class="pure-u-1 is-center">
                <form class="pure-form pure-form-aligned" method="post">
                    <fieldset>
                        <div class="pure-control-group">
                            <label for="email"><?php echo $lang['email']; ?></label>
                            <input id="email" type="email" name="email" required>
                        </div>
                        <div class="pure-control-group">
                            <label for="password"><?php echo $lang['password']; ?></label>
                            <input id="password" type="password" name="password" required>
                        </div>
                        <div class="pure-control-group">
                            <label for="password_confirmation"><?php echo $lang['password_repeat']; ?></label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required>
                        </div>
                        <input type="hidden" name="action" value="register">

                        <div class="pure-controls">
                            <button type="submit" class="pure-button"><?php echo $lang['sign_up!']; ?></button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>

    </div>
    <?php
        }
    ?>

    <?php
        if ($page == "zapomenute") {
    ?>
    <!--<div class="content">
        <h2 class="content-head is-center"><?php echo $lang['password_recovery']; ?></h2>

        <div class="pure-g">
            <div class="pure-u-1 is-center">
                <form class="pure-form pure-form-aligned" method="post">
                    <fieldset>
                        <div class="pure-control-group">
                            <label for="email"><?php echo $lang['email']; ?></label>
                            <input id="email" type="email" name="email" required>
                        </div>
                        <input type="hidden" name="action" value="recover">

                        <div class="pure-controls">
                            <button type="submit" class="pure-button"><?php echo $lang['send']; ?></button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>

    </div>-->
    <?php
        }
    ?>

    <?php
        if ($page == "prihlaska") {
            if (!isset($_SESSION["token"])) {
                echo "<script> window.location.replace('$home'); </script>";
            }
    ?>
    <div class="content">
        <h2 class="content-head is-center"><?php echo $lang['event']; ?></h2>
        <p><?php echo $lang['location']; ?>, <?php echo $lang['date']; ?></p>


        <div class="pure-g">
            <div class="l-box pure-u-1 pure-u-md-1-4">
                <h3 class="content-subhead"><?php echo $lang['team_application']; ?></h3>
                <p><?php echo $lang['team_application_details']; ?></p>
                <a href="?p=24-6-tym" class="pure-button"><?php echo $lang['apply']; ?></a>
            </div>
            <div class="l-box pure-u-1 pure-u-md-1-4">
                <h3 class="content-subhead"><?php echo $lang['adjudicator_application']; ?></h3>
                <p><?php echo $lang['adjudicator_application_details']; ?></p>
                <a href="?p=24-6-rozhodci" class="pure-button"><?php echo $lang['apply']; ?></a>
            </div>
            <div class="l-box pure-u-1 pure-u-md-1-4">
                <h3 class="content-subhead"><?php echo $lang['teacher_application']; ?></h3>
                <p><?php echo $lang['teacher_application_details']; ?></p>
                <a href="?p=24-6-dozor" class="pure-button"><?php echo $lang['apply']; ?></a>
            </div>
            <div class="l-box pure-u-1 pure-u-md-1-4">
                <h3 class="content-subhead"><?php echo $lang['single_application']; ?></h3>
                <p><?php echo $lang['single_application_details']; ?></p>
                <a href="?p=24-6-jednotlivec" class="pure-button"><?php echo $lang['apply']; ?></a>
            </div>
        </div>
    </div>
    <?php
        }
    ?>

    <?php
        if ($page == "seznam") {
            if (!isset($_SESSION["token"])) {
                echo "<script> window.location.replace('$home'); </script>";
            }
    ?>
    <div class="content">
        <h2 class="content-head is-center"><?php echo $lang['list_of_applied']; ?></h2>
        <div class="pure-u-1">
            <?php
                $applied = getApplied("24-6");

                if (empty($applied['response'])) {
                    echo "<p>$lang[no_applied]</p>";
                } else {
                    foreach ($applied["response"] as $person) {
                        switch ($person["event"]) {
                            case '24-6-rozhodci':
                                $persons['adjudicators']['persons'][] = $person;
                                $persons['adjudicators']['name'] = $lang['adjudicators'];
                                break;
                            case '24-6-dozor':
                                $persons['teachers']['persons'][] = $person;
                                $persons['teachers']['name'] = $lang['teachers'];
                                break;
                            case '24-6-tym':
                                (string) $teamname = $person['teamname'];
                                $teams[$teamname][] = $person;
                                break;
                            case '24-6-jednotlivec':
                                $persons['singles']['persons'][] = $person;
                                $persons['singles']['name'] = $lang['singles'];
                                break;
                            case '24-6-kouc':
                                $persons['coaches']['persons'][] = $person;
                                $persons['coaches']['name'] = $lang['coaches_training'];
                                break;
                        }
                    }

                    echo "<ul>";
                    foreach ($persons as $category) {
                        if (!empty($category['persons'])) {
                            echo "<li>$category[name]</li>";
                            echo "<ul>";
                            foreach ($category['persons'] as $person) {
                                echo "<li>$person[name] $person[surname]</li>";
                            }
                            echo "</ul>";
                        } 
                    }
                    if (!empty($teams)) {
                        echo "<li>$lang[teams]</li>";
                        echo "<ul>";
                        foreach ($teams as $teamname => $debaters) {
                            echo "<li>$teamname</li>";
                            echo "<ul>";
                            foreach ($debaters as $debater) {
                                echo "<li>$debater[name] $debater[surname]</li>";
                            }
                            echo "</ul>";
                        }
                        echo "</ul>";       
                    }
                    echo "</ul>";
                }
            ?>
        </div>
    </div>
    <?php
        }
    ?>


    <?php
        if ($page == "24-6-rozhodci" or $page == "24-6-jednotlivec" or $page == "24-6-dozor" or $page == "24-6-kouc") {
            if (!isset($_SESSION["token"])) {
                echo "<script> window.location.replace('$home'); </script>";
            }
            switch ($page) {
                case "24-6-rozhodci":
                    $head = $lang['adjudicator_application'];
                    break;
                case "24-6-jednotlivec":
                    $head = $lang['single_application'];
                    break;
                case "24-6-dozor":
                    $head = $lang['teacher_application'];
                    break;
                case "24-6-kouc":
                    $head = $lang['coach_application'];
                    break;
            }
    ?>
    <div class="content">
        <h2 class="content-head is-center"><?php echo $head; ?></h2>

        <div class="pure-g">
            <div class="pure-u-1 pure-u-md-1-2">
                <?php echo $lang['conditions']; ?>
            </div>
            <div class="pure-u-1 pure-u-md-1-3 is-center">
                <form class="pure-form pure-form-aligned" method="post">
                    <fieldset>
                        <div class="pure-control-group">
                            <label for="name"><?php echo $lang['name']; ?></label>
                            <input id="name" type="text" name="name" required>
                        </div>
                        <div class="pure-control-group">
                            <label for="surname"><?php echo $lang['surname']; ?></label>
                            <input id="surname" type="text" name="surname" required>
                        </div>
                        <div class="pure-control-group">
                            <label for="day"><?php echo $lang['birthdate']; ?></label>
                            <select id="day" name="day">
                                <?php
                                    for ($i = 1; $i <= 31; $i++) {
                                        echo "<option value=\"$i\">$i</option>";
                                    }
                                ?>
                            </select>
                            <select id="month" name="month">
                                <?php
                                    for ($i = 1; $i <= 12; $i++) {
                                        $j = $i-1;
                                        echo "<option value=\"$i\">" . $lang['months'][$j] . "</option>";
                                    }
                                ?>
                            </select>
                            <select id="year" name="year">
                                <?php
                                    for ($i = 0; $i <= 99; $i++) {
                                        $j = 2018-$i;
                                        echo "<option value=\"$j\">$j</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="pure-control-group">
                            <label for="op"><?php echo $lang['id_number']; ?></label>
                            <input id="op" type="text" name="op">
                        </div>
                        <div class="pure-control-group">
                            <label for="street"><?php echo $lang['street_number']; ?></label>
                            <input id="street" type="text" class="smartform-street-and-number" name="street" required>
                        </div>
                        <div class="pure-control-group">
                            <label for="city"><?php echo $lang['city']; ?></label>
                            <input id="city" type="text" class="smartform-city" name="city" required>
                        </div>
                        <div class="pure-control-group">
                            <label for="zip"><?php echo $lang['zip']; ?></label>
                            <input id="zip" type="text" class="smartform-zip" name="zip" required>
                        </div>
                        <div class="pure-control-group">
                            <label for="comment"><?php echo $lang['note']; ?></label>
                            <textarea id="comment" type="text" name="note" placeholder="<?php echo $lang['note_example']; ?>"></textarea>
                        </div>
                        <div class="pure-control-group">
                            <label for="agreement"><?php echo $lang['agreement']; ?></label>
                            <input id="agreement" type="checkbox" name="agreement" required>
                        </div>
                        <input type="hidden" name="event" value="<?php echo $page; ?>">
                        <input type="hidden" name="action" value="application">

                        <div class="pure-controls">
                            <button type="submit" class="pure-button"><?php echo $lang['apply']; ?></button>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="pure-u-1 pure-u-md-1-6">
                <?php
                    $appliedBefore = getAppliedBefore();
                    if (!empty($appliedBefore["response"])) {
                        echo "<h3>".$lang['applied_before']."</h3>";
                        echo "<ul>";
                        foreach ($appliedBefore["response"] as $person) {
                            $birthDate = explode('-', $person['birthdate']);
                            echo "<li><a href=\"#\" onclick=\"fillAppliedBefore('$person[name]', '$person[surname]', $birthDate[2], $birthDate[1], $birthDate[0], '$person[id_number]', '$person[street]', '$person[city]', '$person[zip]')\">".$person['name']." ".$person['surname']."</a></li>";
                        }
                        echo "</ul>";
                    }
                ?>
            </div>
        </div>
    </div>
    <?php
        }
    ?>

    <?php
        if ($page == "24-6-tym") {
            if (!isset($_SESSION["token"])) {
                echo "<script> window.location.replace('$home'); </script>";
            }
    ?>
    <div class="content">
        <h2 class="content-head is-center"><?php echo $lang['apply']; ?></h2>

        <div class="pure-g">
            <div class="pure-u-1">
                <?php echo $lang['conditions']; ?>
            </div>
            <form class="pure-form pure-form-stacked" method="post">
                <fieldset>
                    <div class="pure-u-1">
                        <label for="team-name"><?php echo $lang['team_name']; ?></label>
                        <input id="team-name" type="text" name="team-name" required>
                    </div>
                </fieldset>

                <div class="pure-u-1 pure-u-md-5-6">
                    <div id="debater-line-1"></div>
                    <div id="debater-line-2"></div>
                    <div id="debater-line-3"></div>
                    <div id="debater-line-4"></div>
                    <div id="debater-line-5"></div>
                </div>
                <div id="applied-before" class="pure-u-1 pure-u-md-1-6" style="visibility: hidden">
                    <?php
                        $appliedBefore = getAppliedBefore();
                        if (!empty($appliedBefore["response"])) {
                            echo "<h3>".$lang['applied_before']."</h3>";
                            echo "<ul>";
                            foreach ($appliedBefore["response"] as $person) {
                                $birthDate = explode('-', $person['birthdate']);
                                echo "<li><a href=\"#\" onclick=\"fillAppliedBefore('$person[name]', '$person[surname]', $birthDate[2], $birthDate[1], $birthDate[0], '$person[id_number]', '$person[street]', '$person[city]', '$person[zip]')\">".$person['name']." ".$person['surname']."</a></li>";
                            }
                            echo "</ul>";
                        }
                    ?>
                </div>

                <div class="pure-u-1">
                    <label for="agreement"><?php echo $lang['agreement']; ?></label>
                    <input id="agreement" type="checkbox" name="agreement" required>
                </div>
                <input type="hidden" name="event" value="<?php echo $page; ?>">
                <input type="hidden" name="action" value="team-application">

                <div class="pure-controls">
                    <button type="button" id="next-debater" class="pure-button pure-button-primary" onclick="loadDebaterLine(1,'<?php echo $language; ?>');"><?php echo $lang['add_debater']; ?></button>
                    <button type="button" id="remove-debater" class="button-red pure-button" onclick="deleteDebaterLine(1,'<?php echo $language; ?>');" style="visibility: hidden;"><?php echo $lang['remove_debater']; ?></button>
                    <button type="submit" id="apply" class="pure-button" style="visibility: hidden;"><?php echo $lang['apply']; ?></button>
                </div>
            </form>
        </div>
    </div>
    <?php
        }
    ?>

<?php
    if (isset($_POST["action"])) {
        $ch = curl_init();

        switch ($_POST["action"]) {
            case 'register':
                $urlFinal = $url."user";
                $data = array(
                    "username" => $_POST["email"],
                    "password" => $_POST["password"],
                    "password_confirmation" => $_POST["password_confirmation"],
                    "person_id" => null
                );
                break;

            case 'login':
                $urlFinal = $url."login";
                $data = array(
                    "username" => $_POST["email"],
                    "password" => $_POST["password"]
                );
                break;

            case 'recover':
                $urlFinal = $url."recover";
                $data = array (
                    "username" => $_POST["email"]
                );
                break;

            case 'application':
                $urlFinal = $url."registration";
                $data = array(
                    "api_token" => $_SESSION["token"],
                    "name" => $_POST["name"],
                    "surname" => $_POST["surname"],
                    "birthdate" => $_POST["year"]."-".$_POST["month"]."-".$_POST["day"],
                    "id_number" => $_POST["op"],
                    "street" => $_POST["street"],
                    "city" => $_POST["city"],
                    "zip" => $_POST["zip"],
                    "note" => !empty($_POST["note"]) ? $_POST["note"] : null,
                    "event" => $_POST["event"]
                );               
                break;

            case 'team-application':
                $urlFinal = $url."team";
                $data = array (
                    "api_token" => $_SESSION["token"],
                    "name" => $_POST["team-name"],
                    "event" => $_POST["event"]
                );
                break;
        }

        curl_setopt($ch, CURLOPT_URL, $urlFinal);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = json_decode(curl_exec($ch), true);
        $code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        curl_close($ch);


        switch ($_POST["action"]) {
            case 'register':
                if ($code == 201) {
                    $ch = curl_init();

                    $data = array(
                        "username" => $_POST["email"],
                        "password" => $_POST["password"]
                    );

                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                    curl_setopt($ch, CURLOPT_URL, $url."login");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    $response = json_decode(curl_exec($ch), true);
                    $code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

                    curl_close($ch);
                } elseif ($code == 409) {
                    echo '<p class="is-center">' . $lang['user_exists'] . ' <a href="?p=prihlaseni">' . $lang['please_login'] . '</a>, ' . $lang['use_another'] . '.</p>';
                    break;
                } elseif ($code == 422) {
                    if ($response["password"][0] == "The password confirmation does not match.") {
                        echo '<p class="is-center">' . $lang['password_mismatch'] . '</p>';
                    } elseif ($response["password"][0] == "The password format is invalid.") {
                        echo '<p class="is-center">' . $lang['wrong_format'] . '</p>';
                    } elseif ($response["password"][0] == "The password must be at least 8 characters.") {
                        echo '<p class="is-center">' . $lang['eight_characters'] . '</p>';
                    }
                    break;
                }

            case 'login':
                if ($code == 200) {
                    $_SESSION["user_id"] = $response["id"];
                    $_SESSION["email"] = $response["username"];
                    $_SESSION["token"] = $response["api_token"];
                    $_SESSION["last_login"] = time();

                    echo "<script> window.location.replace('?p=prihlaska'); </script>";
                } elseif ($code == 401 or $code == 422 or $code == 500) {
                    echo '<p class="is-center">' . $lang['wrong_credentials'] . '</p>';
                }
                break;

            case 'application':
                if ($code == 201) {
                    echo "<script> window.location.replace('?p=prijata'); </script>";
                }
                break;

            case 'team-application':
                if ($code == 201) {
                    $i = 1;
                    $team = $response["id"];

                    while (isset($_POST["name-$i"])) {
                        $ch = curl_init();

                        $data = array(
                            "api_token" => $_SESSION["token"],
                            "name" => $_POST["name-$i"],
                            "surname" => $_POST["surname-$i"],
                            "birthdate" => $_POST["year-$i"]."-".$_POST["month-$i"]."-".$_POST["day-$i"],
                            "id_number" => $_POST["op-$i"],
                            "street" => $_POST["street-$i"],
                            "city" => $_POST["city-$i"],
                            "zip" => $_POST["zip-$i"],
                            "note" => !empty($_POST["note-$i"]) ? $_POST["note-$i"] : null,
                            "event" => $_POST["event"],
                            "team" => $team
                        );

                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                        curl_setopt($ch, CURLOPT_URL, $url."registration");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        $debater = json_decode(curl_exec($ch), true);
                        $code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

                        curl_close($ch);

                        $i++;
                    }

                    if ($code == 201) {
                        echo "<script> window.location.replace('?p=prijata'); </script>";
                    }
                }

                break;
            default:
                # code...
                break;
        }
    }
?>

    <footer class="footer l-box is-center">
        2018 Asociace debatních klubů, z.s.
    </footer>

</div>


</body>
</html>
