export const SessionService = {
    getSessionsData() {
        return [
            {
                id: '1000',
                cashier_id: '1',
                start_time: 'March 24, 2024 (Sun) 3:34pm',
                status: 'Open',
                opening_cash: 200,
                note: 'I dont know'
            },
            {
                id: '1001',
                cashier_id: '2',
                start_time: 'March 25, 2024 (Mon) 10:00am',
                status: 'Closed',
                opening_cash: 300,
                note: 'No notes'
            },
            // Add more session objects as needed
            {
                id: '1002',
                cashier_id: '3',
                start_time: 'March 26, 2024 (Tue) 2:00pm',
                status: 'Open',
                opening_cash: 150,
                note: 'Some notes here'
            },
            {
                id: '1003',
                cashier_id: '4',
                start_time: 'March 27, 2024 (Wed) 9:30am',
                status: 'Closed',
                opening_cash: 400,
                note: 'More notes'
            }
            // Add more session objects as needed
        ];
    },
    getSessionsSmall() {
        return Promise.resolve(this.getSessionsData().slice(0, 10));
    }
};
