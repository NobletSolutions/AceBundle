<?php
namespace NS\AceBundle\Service;

use IntlDateFormatter;
use Locale;
/**
 * Description of DateFormatConverter
 *
 * @author mark
 */
class DateFormatConverter {
     
    public function getFormat($longyear = false)
    {
        $formatter = new \IntlDateFormatter(
                Locale::getDefault(),
                IntlDateFormatter::SHORT,
                IntlDateFormatter::NONE,
                'UTC',
                \IntlDateFormatter::GREGORIAN,
                null
            );
        
        $formatter->setLenient(false);
        
        $pattern = $formatter->getPattern();
        
        if($longyear && strpos($pattern, 'yyyy') === false)
            $pattern = str_replace('yy', 'yyyy', $pattern);
        
        if(strpos($pattern, 'MM') === false)
            $pattern = str_replace('M', 'MM', $pattern);
        
        if(strpos($pattern, 'dd') === false)
            $pattern = str_replace('d', 'dd', $pattern);
        
        return trim(str_replace(' ', '', $pattern), './-');
    }
}
