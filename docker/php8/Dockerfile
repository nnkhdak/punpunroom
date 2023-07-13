FROM amazonlinux:2023
# FROM --platform=linux/x86_64 amazonlinux:2023

# yum update & install
RUN yum update -y \
    && yum install -y systemd httpd

# httpd
RUN systemctl enable httpd

# init
CMD ["/sbin/init"]