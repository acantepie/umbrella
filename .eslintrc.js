module.exports = {
    'extends': 'eslint:recommended',
    'parser': '@babel/eslint-parser',
    'env': {
        'browser': true,
        'es6': true,
        jquery: true,
    },
    'globals': {
        'umbrella': true,
        'flatpickr': true,
        'bootstrap': true
    },
    'parserOptions': {
        'requireConfigFile': false,
        'ecmaVersion': 12,
        'sourceType': 'module'
    },
    'rules': {
        indent: ['error', 4],
        quotes: ['error', 'single'],
        'no-unused-vars': ['off'],
        'no-prototype-builtins': ['off']
    }
};
