<?php
$language = $_REQUEST['lang'];
include("languages/$language.php");
?>
<legend class="pure-u-1 pure-u-md-4-6"><?php echo $lang['debater'] . " " . $_REQUEST["number"]; ?></legend>
<fieldset>
    <div class="pure-u-1 pure-u-md-1-5">
        <label for="name"><?php echo $lang['name']; ?></label>
        <input id="name-<?php echo $_REQUEST["number"]; ?>" type="text" name="name-<?php echo $_REQUEST["number"]; ?>" required>
    </div>
    <div class="pure-u-1 pure-u-md-1-5">
        <label for="surname"><?php echo $lang['surname']; ?></label>
        <input id="surname-<?php echo $_REQUEST["number"]; ?>" type="text" name="surname-<?php echo $_REQUEST["number"]; ?>" required>
    </div>
    <div class="pure-u-1 pure-u-md-1-5 pure-form-aligned">
        <label for="day"><?php echo $lang['birthdate']; ?></label>
        <select id="day-<?php echo $_REQUEST["number"]; ?>" name="day-<?php echo $_REQUEST["number"]; ?>">
            <?php
                for ($i = 1; $i <= 31; $i++) {
                    echo "<option value=\"$i\">$i</option>";
                }
            ?>
        </select>
        <select id="month-<?php echo $_REQUEST["number"]; ?>" name="month-<?php echo $_REQUEST["number"]; ?>">
            <?php
                for ($i = 1; $i <= 12; $i++) {
                    $j = $i-1;
                    echo "<option value=\"$i\">" . $lang['months'][$j] . "</option>";
                }
            ?>
        </select>
        <select id="year-<?php echo $_REQUEST["number"]; ?>" name="year-<?php echo $_REQUEST["number"]; ?>">
            <?php
                for ($i = 0; $i <= 99; $i++) {
                    $j = 2018-$i;
                    echo "<option value=\"$j\">$j</option>";
                }
            ?>
        </select>
    </div>
    <div class="pure-u-1 pure-u-md-1-5">
        <label for="op"><?php echo $lang['id_number']; ?></label>
        <input id="op-<?php echo $_REQUEST["number"]; ?>" type="text" name="op-<?php echo $_REQUEST["number"]; ?>">
    </div>
    <div class="pure-u-1 pure-u-md-1-5">
        <label for="street"><?php echo $lang['street_number']; ?></label>
        <input id="street-<?php echo $_REQUEST["number"]; ?>" type="text" class="smartform-instance-<?php echo $_REQUEST["number"]; ?> smartform-street-and-number" name="street-<?php echo $_REQUEST["number"]; ?>" required>
    </div>
    <div class="pure-u-1 pure-u-md-1-5">
        <label for="city"><?php echo $lang['city']; ?></label>
        <input id="city-<?php echo $_REQUEST["number"]; ?>" type="text" class="smartform-instance-<?php echo $_REQUEST["number"]; ?> smartform-city" name="city-<?php echo $_REQUEST["number"]; ?>" required>
    </div>
    <div class="pure-u-1 pure-u-md-1-5">
        <label for="zip"><?php echo $lang['zip']; ?></label>
        <input id="zip-<?php echo $_REQUEST["number"]; ?>" type="text" class="smartform-instance-<?php echo $_REQUEST["number"]; ?> smartform-zip" name="zip-<?php echo $_REQUEST["number"]; ?>" required>
    </div>
    <div class="pure-u-1 pure-u-md-1-5">
        <label for="comment"><?php echo $lang['note']; ?></label>
        <textarea id="comment-<?php echo $_REQUEST["number"]; ?>" type="text" name="note-<?php echo $_REQUEST["number"]; ?>" placeholder="<?php echo $lang['note_example']; ?>"></textarea>
    </div>
</fieldset>