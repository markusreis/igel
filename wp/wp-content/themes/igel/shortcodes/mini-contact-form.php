<?php

if (!function_exists('render_mini_contact_form')) {

    function render_mini_contact_form($user)
    {
        ?>
        <form action="" class="c-mini-contact" data-js="contact-form" data-js-contact-form="mini">
            <div class="row c-mini-contact__name-row">
                <div class="col-12 col-6@md">
                    <div class="input-wrap">
                        <input type="text" id="firstname" name="firstname" placeholder=" " required>
                        <label for="firstname">Vorname</label>
                    </div>
                </div>
                <div class="col-12 col-6@md">
                    <div class="input-wrap">
                        <input type="text" id="lastname" name="lastname" placeholder=" " required>
                        <label for="lastname">Nachname</label>
                    </div>
                </div>
            </div>
            <div class="row c-mini-contact__details-row">
                <div class="col-12 col-6@md">
                    <div class="input-wrap">
                        <input type="email" id="email" name="email" placeholder=" " required>
                        <label for="email">E-Mail Adresse</label>
                    </div>
                </div>
                <div class="col-12 col-6@md">
                    <div class="input-wrap">
                        <input type="text" id="phone" name="phone" placeholder=" ">
                        <label for="phone">Telefonnummer</label>
                    </div>
                </div>
            </div>

            <div class="c-checkbox">
                <input type="checkbox" name="toc" id="toc" required>
                <label for="toc" class="text-small">
                    Ich stimme der <a onclick="event.stopPropagation();"
                                      href="https://www.igel-immobilien.at/datenschutzerklaerung/" target="_blank">Datenschutzerklärung</a>
                    und einer
                    Kontaktaufnahme durch IGEL Immobilien
                    GmbH per E-Mail oder Telefon für Rückfragen oder zu Informationszwecken zu.
                </label>
            </div>


            <input type="hidden" value="<?php echo $user->ID; ?>"
                   name="agent">
            <input type="hidden" value="<?php echo get_permalink(); ?>" name="url">

            <button type="submit">
                Anfrage absenden
                <i class="button--after ig ig-arrow"></i>
            </button>
        </form>
        <?php
    }
}
