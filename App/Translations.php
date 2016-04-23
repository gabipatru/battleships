<?php

class Translations {
    private static $language;
    private $module;

    private static $translations = array();

    ###################################################################################################################
    ###  Function for module settings
    ###################################################################################################################
    public function setModule($module) {
        if (!$module) {
            return false;
        }

        $this->module = $module;
    }

    public function getModule() {
        if (empty($this->module)) {
            return DEFAULT_MODULE;
        }

        return $this->module;
    }

    ###################################################################################################################
    ###  Function for language settings
    ###################################################################################################################
    public function setLanguage($lang) {
        if (!$lang) {
            return false;
        }

        self::$language = $lang;
    }

    public function getLanguage() {
        if (empty(self::$language)) {
            return DEFAULT_LANGUAGE;
        }

        return self::$language;
    }

    public function __construct($language = DEFAULT_LANGUAGE) {
        $this->setLanguage($language);
    }

    /*
     * Load a translation. Language and module must be set
     */
    public function loadTranslations() {
        $language   = $this->getLanguage();
        $module     = $this->getModule();

        // check if the treanslation is already loaded
        if (!empty(self::$translations[$module])) {
            return;
        }

        // load, process translation
        if (file_exists(TRANSLATIONS_DIR . '/' . $language . '/' . $module . '.csv')) {
            $fis = fopen(TRANSLATIONS_DIR . '/' . $language . '/' . $module . '.csv', "r");
            while($line = fgetcsv($fis)) {
                self::$translations[$module]['untranslated'][] = $line[0];
                self::$translations[$module]['translated'][] = $line[1];
            }
        }
        else {
            Battleships::log('Warning! Translation file ' . $language . '/' . $module . '.csv' . ' Does not exist!');
        }
    }

    /*
     * Translate a simple message
     */
    public function __($msg) {
        $module = $this->getModule();

        if ($key = array_search($msg, self::$translations[$module]['untranslated'])) {
            return self::$translations[$module]['translated'][$key];
        }

        return $msg;
    }

    /*
     * Translate a message with format
     * Uses vsprintf
     */
    public function ___() {
        $module = $this->getModule();

        $numargs = func_num_args();
        if ($numargs < 1) {
            return '';
        }

        // The message to be translated
        $msg = func_get_arg(0);
        if (!$key = array_search($msg, self::$translations[$module]['untranslated'])) {
            return $msg;
        }

        $args = array();
        for ($i=1; $i<$numargs; $i++) {
            $args[] = func_get_arg($i);
        }

        return vsprintf(self::$translations[$module]['translated'][$key], $args);
    }
}