export const stringRegel = [
    value => {
        if (typeof value === 'string' && value.trim() !== '') {
            return true;
        }
        return 'Geben Sie eine gültige Regalbezeichnung ein';
    },
];

export const integerRegel = [
    value => {
        if (!isNaN(value) && value > 0) {
            return true;
        }
        return 'Geben Sie eine gültige Anzahl ein';
    },
];