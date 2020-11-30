class columnName {
    static count: number = 0;

    constructor() {
        columnName.count++;
    }

    getName(): string {
        return `_new_column_${columnName.count}`;
    }
}

export const createColumnName = (): string => {
    return new columnName().getName();
}