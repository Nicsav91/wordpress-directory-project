<table class="form-table">
    <tr>
        <th scope="row"><label for="studio_address">Adress</label></th>
        <td><input type="text" id="studio_address" name="_studio_address" value="<?php echo esc_attr($address); ?>" class="regular-text" /></td>
    </tr>
    <tr>
        <th scope="row"><label for="studio_phone">Telefon</label></th>
        <td><input type="text" id="studio_phone" name="_studio_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" /></td>
    </tr>
    <tr>
        <th scope="row"><label for="studio_email">E-post</label></th>
        <td><input type="email" id="studio_email" name="_studio_email" value="<?php echo esc_attr($email); ?>" class="regular-text" /></td>
    </tr>
    <tr>
        <th scope="row"><label for="studio_website">Webbsida</label></th>
        <td><input type="url" id="studio_website" name="_studio_website" value="<?php echo esc_attr($website); ?>" class="regular-text" /></td>
    </tr>
    <tr>
        <th scope="row"><label for="studio_price_range">Prisintervall</label></th>
        <td>
            <input type="text" id="studio_price_range" name="_studio_price_range" value="<?php echo esc_attr($price_range); ?>" class="regular-text" />
            <p class="description">T.ex: 200-400 kr/klass</p>
        </td>
    </tr>
    <tr>
        <th scope="row">Koordinater</th>
        <td>
            <input type="text" id="studio_latitude" name="_studio_latitude" value="<?php echo esc_attr($latitude); ?>" placeholder="Latitude" />
            <input type="text" id="studio_longitude" name="_studio_longitude" value="<?php echo esc_attr($longitude); ?>" placeholder="Longitude" />
            <p class="description">Kommer att fyllas i automatiskt baserat på adressen</p>
        </td>
    </tr>
</table>

<h3>Öppettider</h3>
<table class="form-table">
    <?php
    $days = array(
        'monday' => 'Måndag',
        'tuesday' => 'Tisdag', 
        'wednesday' => 'Onsdag',
        'thursday' => 'Torsdag',
        'friday' => 'Fredag',
        'saturday' => 'Lördag',
        'sunday' => 'Söndag'
    );
    
    foreach ($days as $day => $label): ?>
    <tr>
        <th scope="row"><?php echo $label; ?></th>
        <td>
            <input type="time" name="_studio_opening_hours[<?php echo $day; ?>][open]" 
                   value="<?php echo esc_attr($opening_hours[$day]['open']); ?>" />
            -
            <input type="time" name="_studio_opening_hours[<?php echo $day; ?>][close]" 
                   value="<?php echo esc_attr($opening_hours[$day]['close']); ?>" />
            <label>
                <input type="checkbox" name="_studio_opening_hours[<?php echo $day; ?>][closed]" 
                       value="1" <?php checked(empty($opening_hours[$day]['open']) && empty($opening_hours[$day]['close'])); ?> />
                Stängt
            </label>
        </td>
    </tr>
    <?php endforeach; ?>
</table>