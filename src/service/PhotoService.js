export const PhotoService = {
    getData() {
        return [
            {
                itemImageSrc: '/src/assets/images/resort-1.jpg',
                alt: 'Description for Image 11',
                title: 'Title 1'
            },
            {
                itemImageSrc: '/src/assets/images/resort-2.avif',
                alt: 'Description for Image 2',
                title: 'Title 2'
            },
            {
                itemImageSrc: '/src/assets/images/resort-3.jpg',
                alt: 'Description for Image 3',
                title: 'Title 3'
            },
            {
                itemImageSrc: '/src/assets/images/resto-1.jpeg',
                alt: 'Description for Image 4',
                title: 'Title 4'
            },
            {
                itemImageSrc: '/src/assets/images/resto-2.jpg',
                alt: 'Description for Image 5',
                title: 'Title 5'
            },
            {
                itemImageSrc: '/src/assets/images/event-1.jpg',
                alt: 'Description for Image 6',
                title: 'Title 6'
            },
            {
                itemImageSrc: '/src/assets/images/event-2.jpg',
                alt: 'Description for Image 7',
                title: 'Title 7'
            }
        ];
    },

    getImages() {
        return Promise.resolve(this.getData());
    }
};
