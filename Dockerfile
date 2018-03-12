FROM ubuntu

#ENV DEBIAN_FRONTEND noninteractive

# Update the repository sources list
RUN apt-get update

RUN apt-get install -y vim

# Install and run apache
RUN apt-get install -y apache2 && apt-get clean

RUN apt-get install -y php libapache2-mod-php  php-pear \
    php-mysql php-curl php-cli
#libapache2-mod-php7.1 php7.1-mysql php7.1-curl php7.1-json

#ENTRYPOINT ["/usr/sbin/apache2", "-k", "start"]


#ENV APACHE_RUN_USER www-data
#ENV APACHE_RUN_GROUP www-data
#ENV APACHE_LOG_DIR /var/log/apache2

#WORKDIR /var/www/html

#RUN rm ./index.html

#COPY ./html .

EXPOSE 80

#ENV DEBIAN_FRONTEND teletype

CMD apachectl -D FOREGROUND
