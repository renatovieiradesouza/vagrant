$script = <<-SCRIPT
cat /configs/id_bionic.pub >> .ssh/authorized_keys
SCRIPT

$scriptMysql = <<-SCRIPT
    apt-get update && \
    apt-get install -y mysql-server-5.7
    mysql -e "create user 'phpuser'@'%'identified by 'pass'; "
SCRIPT

$scriptChangeBindIpDefaultMysql = <<-SCRIPT
    cat /configs/mysqld.cnf > /etc/mysql/mysql.conf.d/mysqld.cnf
    service mysql restart
SCRIPT

$installPuppet = <<-SCRIPT
    apt-get update && apt-get install -y puppet
SCRIPT

Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/bionic64"

  config.vm.define "mysqldb" do |mysql|
    mysql.vm.network "private_network", ip: "192.168.40.4"
    #mysql.vm.network "private_network", type: "dhcp"
    #mysql.vm.network "public_network", ip: "192.168.0.145"
    #mysql.vm.provision "shell", inline: "echo Hello, World >> hello.txt"
    #Você pode criar functions(provisioners) para chamar nos provisioners, veja
    mysql.vm.provision "shell", inline: $script
    mysql.vm.provision "shell", inline: $scriptMysql
    mysql.vm.provision "shell", inline: $scriptChangeBindIpDefaultMysql
    mysql.vm.synced_folder "./configs", "/configs"
    mysql.vm.synced_folder ".", "/vagrant", disabled: true  
  end

  config.vm.define "phpweb" do |phpweb|
    phpweb.vm.network "forwarded_port", guest: 8888, host: 8888
    phpweb.vm.network "private_network", ip: "192.168.40.55"

    phpweb.vm.provision "shell", inline: $installPuppet

    #Depois de instalar o puppet, vamos instalar os itens provisionados no arquivo phpweb.pp em configs/manifests
    phpweb.vm.provision "puppet" do |puppet|
        puppet.manifests_path = "./configs/manifests"
        puppet.manifest_file = "phpweb.pp"
    end
  end
  
end

