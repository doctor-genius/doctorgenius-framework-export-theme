<?php

$fw_options = get_option( 'dg_options' );

if ( array_key_exists( 'translator_toggle', $fw_options ) && $fw_options['translator_toggle'] == TRUE ) {
	//add_action( 'wp_footer', 'dg_enable_translator' );
}

function dg_translator_codes() { 
    
    return array(
        'Afrikaans' => 'af',
        'Albanian' => 'sq',
        'Amharic' => 'am',
        'Arabic' => 'ar',
        'Armenian' => 'hy',
        'Azeerbaijani' => 'az',
        'Basque' => 'eu',
        'Belarusian' => 'be',
        'Bengali' => 'bn',
        'Bosnian' => 'bs',
        'Bulgarian' => 'bg',
        'Catalan' => 'ca',
        'Cebuano' => 'ceb',
        'Chinese (Simplified)' => 'zh-CN',
        'Chinese (Traditional)' => 'zh-TW',
        'Corsican' => 'co',
        'Croatian' => 'hr',
        'Czech' => 'cs',
        'Danish' => 'da',
        'Dutch' => 'nl',
        'English' => 'en',
        'Esperanto' => 'eo',
        'Estonian' => 'et',
        'Finnish' => 'fi',
        'French' => 'fr',
        'Frisian' => 'fy',
        'Galician' => 'gl',
        'Georgian' => 'ka',
        'German' => 'de',
        'Greek' => 'el',
        'Gujarati' => 'gu',
        'Haitian Creole' => 'ht',
        'Hausa' => 'ha',
        'Hawaiian' => 'haw',
        'Hebrew' => 'iw',
        'Hindi' => 'hi',
        'Hmong' => 'hmn',
        'Hungarian' => 'hu',
        'Icelandic' => 'is',
        'Igbo' => 'ig',
        'Indonesian' => 'id',
        'Irish' => 'ga',
        'Italian' => 'it',
        'Japanese' => 'ja',
        'Javanese' => 'jw',
        'Kannada' => 'kn',
        'Kazakh' => 'kk',
        'Khmer' => 'km',
        'Korean' => 'ko',
        'Kurdish' => 'ku',
        'Kyrgyz' => 'ky',
        'Lao' => 'lo',
        'Latin' => 'la',
        'Latvian' => 'lv',
        'Lithuanian' => 'lt',
        'Luxembourgish' => 'lb',
        'Macedonian' => 'mk',
        'Malagasy' => 'mg',
        'Malay' => 'ms',
        'Malayalam' => 'ml',
        'Maltese' => 'mt',
        'Maori' => 'mi',
        'Marathi' => 'mr',
        'Mongolian' => 'mn',
        'Myanmar (Burmese)' => 'my',
        'Nepali' => 'ne',
        'Norwegian' => 'no',
        'Nyanja (Chichewa)' => 'ny',
        'Pashto' => 'ps',
        'Persian' => 'fa',
        'Polish' => 'pl',
        'Portuguese (Portugal, Brazil)' => 'pt',
        'Punjabi' => 'pa',
        'Romanian' => 'ro',
        'Russian' => 'ru',
        'Samoan' => 'sm',
        'Scots Gaelic' => 'gd',
        'Serbian' => 'sr',
        'Sesotho' => 'st',
        'Shona' => 'sn',
        'Sindhi' => 'sd',
        'Sinhala (Sinhalese)' => 'si',
        'Slovak' => 'sk',
        'Slovenian' => 'sl',
        'Somali' => 'so',
        'Spanish' => 'es',
        'Sundanese' => 'su',
        'Swahili' => 'sw',
        'Swedish' => 'sv',
        'Tagalog (Filipino)' => 'tl',
        'Tajik' => 'tg',
        'Tamil' => 'ta',
        'Telugu' => 'te',
        'Thai' => 'th',
        'Turkish' => 'tr',
        'Ukrainian' => 'uk',
        'Urdu' => 'ur',
        'Uzbek' => 'uz',
        'Vietnamese' => 'vi',
        'Welsh' => 'cy',
        'Xhosa' => 'xh',
        'Yiddish' => 'yi',
        'Yoruba' => 'yo',
        'Zulu' => 'zu',
    );
}

function dg_enable_translator() {
    $fw_options = get_option( 'dg_options' );
    $languages = $fw_options['translator_languages'];
    $codes = dg_translator_codes();
    
    $php_data['enabled_languages'] = $languages;
    $php_data['language_codes'] = $codes;
    
    insert_translator_selector_markup( $languages, $codes );
    
    wp_enqueue_script( 'google_translator_internal_scripts', get_template_directory_uri() . '/js/google-translator.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'google_translator_external_scripts', '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit', array( 'jquery', 'google_translator_internal_scripts' ), '1.0.0', true );
    
    wp_enqueue_style( 'google_translator', get_template_directory_uri() . '/css/google-translator.css');
    wp_localize_script( 'google_translator_internal_scripts', 'php_vars', $php_data );
}

function insert_translator_selector_markup( $enabled_languages, $language_codes ) {
	?>
    <section class="google-translator-sticky hide-on-small-only">
        <div id="translator" class="fixed-action-btn"> <!-- for toggle add class "click-to-toggle" --> 
            <a href="#" class="btn-floating btn-large waves-effect waves-light hoverable black" title="Translate This Page">
                <!--<span id="current_lang" class="english">English</span>-->
                <i class="fa fa-globe"></i>
            </a>
            <ul id="translation_links" role="menu">   
                
                
            
                <?php foreach( $enabled_languages as $language ) :
                    $language_name = array_search( $language, $language_codes );
                    ?>
                    <li>
                        <a href="#" title="<?php echo $language_name; ?>" class="<?php echo strtolower( $language_name ); ?> btn btn-floating waves-effect waves-light black" data-lang="<?php echo $language; ?>"><?php echo $language_name; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div id="google_translate_element"></div>
        </div><!-- /#translator -->
    </section>
	<?php
}

function insert_translator_selector_markup_mobile( ) {
    $fw_options = get_option( 'dg_options' );
    $codes = dg_translator_codes();

    //$php_data['enabled_languages'] = $languages;
    //$php_data['language_codes'] = $codes;
    ?>
    
    <div class="google-translator-sticky mobile">
        <div id="translator" class="fixed-action-btn click-to-toggle">
            <a class="btn-floating btn-large waves-effect waves-light hoverable black">
                <i class="fa fa-globe"></i>
            </a>

            <ul id="translation_links" role="menu">
                <?php foreach( $fw_options['translator_languages'] as $language ) :
                    $language_name = array_search( $language, $codes );
                    ?>
                    <li>
                        <a href="#" title="<?php echo $language_name; ?>" class="<?php echo strtolower( $language_name ); ?> btn btn-floating waves-effect waves-light black" data-lang="<?php echo $language; ?>"><?php echo $language_name; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>




    
    <?php
}
