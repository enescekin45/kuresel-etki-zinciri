<?php
/**
 * Language Management Class
 * 
 * Handles multilingual support for the application
 */

class Language {
    private static $instance = null;
    private $currentLanguage = 'tr';
    private $supportedLanguages = [
        'tr' => 'Türkçe',
        'en' => 'English',
        'es' => 'Español',
        'fr' => 'Français',
        'de' => 'Deutsch',
        'it' => 'Italiano',
        'pt' => 'Português',
        'ru' => 'Русский',
        'zh' => '中文',
        'ja' => '日本語',
        'ko' => '한국어',
        'ar' => 'العربية',
        'hi' => 'हिन्दी',
        'bn' => 'বাংলা',
        'pa' => 'ਪੰਜਾਬੀ',
        'jv' => 'Basa Jawa',
        'ms' => 'Bahasa Melayu',
        'vi' => 'Tiếng Việt',
        'th' => 'ไทย',
        'id' => 'Bahasa Indonesia',
        'pl' => 'Polski',
        'uk' => 'Українська',
        'ro' => 'Română'
    ];
    private $translations = [];
    
    private function __construct() {
        $this->initializeLanguage();
        $this->loadTranslations();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Language();
        }
        return self::$instance;
    }
    
    private function initializeLanguage() {
        // Check if language is set in session
        if (isset($_SESSION['language']) && $this->isLanguageSupported($_SESSION['language'])) {
            $this->currentLanguage = $_SESSION['language'];
        } 
        // Check if language is set in URL
        elseif (isset($_GET['lang']) && $this->isLanguageSupported($_GET['lang'])) {
            $this->currentLanguage = $_GET['lang'];
            $_SESSION['language'] = $this->currentLanguage;
        } 
        // Check browser language
        elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            if ($this->isLanguageSupported($browserLang)) {
                $this->currentLanguage = $browserLang;
                $_SESSION['language'] = $this->currentLanguage;
            }
        }
    }
    
    public function isLanguageSupported($langCode) {
        return array_key_exists($langCode, $this->supportedLanguages);
    }
    
    public function setCurrentLanguage($langCode) {
        if ($this->isLanguageSupported($langCode)) {
            $this->currentLanguage = $langCode;
            $_SESSION['language'] = $langCode;
            $this->loadTranslations();
            return true;
        }
        return false;
    }
    
    public function getCurrentLanguage() {
        return $this->currentLanguage;
    }
    
    public function getCurrentLanguageName() {
        return $this->supportedLanguages[$this->currentLanguage] ?? 'Unknown';
    }
    
    public function getSupportedLanguages() {
        return $this->supportedLanguages;
    }
    
    private function loadTranslations() {
        $translationFile = ROOT_DIR . '/lang/' . $this->currentLanguage . '.php';
        
        if (file_exists($translationFile)) {
            $this->translations = require $translationFile;
        } else {
            // Load default Turkish translations
            $defaultFile = ROOT_DIR . '/lang/tr.php';
            if (file_exists($defaultFile)) {
                $this->translations = require $defaultFile;
            }
        }
    }
    
    public function translate($key, $params = []) {
        $translation = $this->translations[$key] ?? $key;
        
        // Replace placeholders with parameters
        foreach ($params as $paramKey => $paramValue) {
            $translation = str_replace('{' . $paramKey . '}', $paramValue, $translation);
        }
        
        return $translation;
    }
    
    public function getTranslations() {
        return $this->translations;
    }
    
    // Get direction of text (ltr or rtl)
    public function getTextDirection() {
        $rtlLanguages = ['ar', 'he', 'fa', 'ur'];
        return in_array($this->currentLanguage, $rtlLanguages) ? 'rtl' : 'ltr';
    }
}
?>