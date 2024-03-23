export const OrderService = {
    getOrdersData() {
        return [
            {
                id: 1000,
                customer_id: 1,
                cashier_id: 4,
                order_date: 'March 24, 2024 (Sun) 3:34pm',
                total_order_price: 2000,
                status: 'Paid',
                session_id: 2,
                tendered: 2500,
                change: 500,
                details: [
                    {
                        id: 1,
                        menu_item_id: 1,
                        subtotal: 1000,
                        quantity: 2
                    }
                ]
            },
            {
                id: 1001,
                customer_id: 2,
                cashier_id: 3,
                order_date: 'March 25, 2024 (Mon) 5:12pm',
                total_order_price: 1500,
                status: 'Pending',
                session_id: 1,
                tendered: 2000,
                change: 500,
                details: [
                    {
                        id: 2,
                        menu_item_id: 3,
                        subtotal: 750,
                        quantity: 1
                    },
                    {
                        id: 3,
                        menu_item_id: 5,
                        subtotal: 750,
                        quantity: 1
                    }
                ]
            },
            {
                id: 1002,
                customer_id: 3,
                cashier_id: 2,
                order_date: 'March 26, 2024 (Tue) 1:45pm',
                total_order_price: 1800,
                status: 'Cancelled',
                session_id: 3,
                tendered: 2000,
                change: 200,
                details: [
                    {
                        id: 4,
                        menu_item_id: 2,
                        subtotal: 600,
                        quantity: 2
                    },
                    {
                        id: 5,
                        menu_item_id: 4,
                        subtotal: 600,
                        quantity: 1
                    },
                    {
                        id: 6,
                        menu_item_id: 6,
                        subtotal: 600,
                        quantity: 1
                    }
                ]
            },
            // Add more orders here
        ];
    }
};
