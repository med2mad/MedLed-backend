FROM node:current-alpine3.21
WORKDIR /app
COPY . /app
RUN npm install
EXPOSE 4000
CMD ["node", "index.js"] 
