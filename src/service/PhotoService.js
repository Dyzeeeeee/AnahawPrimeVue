export const PhotoService = {
    getData() {
        return [
            {
                itemImageSrc: 'resort-1.jpg',
                alt: 'Description for Image 11',
                title: 'Title 1'
            },
            {
                itemImageSrc: 'resort-2.avif',
                alt: 'Description for Image 2',
                title: 'Title 2'
            },
            {
                itemImageSrc: 'resort-3.jpg',
                alt: 'Description for Image 3',
                title: 'Title 3'
            },
            {
                itemImageSrc: 'resto-1.jpeg',
                alt: 'Description for Image 4',
                title: 'Title 4'
            },
            {
                itemImageSrc: 'resto-2.jpg',
                alt: 'Description for Image 5',
                title: 'Title 5'
            },
            {
                itemImageSrc: 'event-1.jpg',
                alt: 'Description for Image 6',
                title: 'Title 6'
            },
            {
                itemImageSrc: 'event-2.jpg',
                alt: 'Description for Image 7',
                title: 'Title 7'
            }
        ];
    },

    getImages() {
        return Promise.resolve(this.getData());
    }
};
