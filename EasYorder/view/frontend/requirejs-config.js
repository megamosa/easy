/**
 * MagoArab_EasYorder RequireJS Configuration
 * 
 * @category    MagoArab
 * @package     MagoArab_EasYorder
 * @author      MagoArab Team
 * @copyright   Copyright (c) 2024 MagoArab (https://magoarab.com)
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */

var config = {
    map: {
        '*': {
            magoarabEasyorder: 'MagoArab_EasYorder/js/easyorder'
        }
    },
    shim: {
        'MagoArab_EasYorder/js/easyorder': {
            deps: ['jquery', 'mage/validation']
        }
    }
};