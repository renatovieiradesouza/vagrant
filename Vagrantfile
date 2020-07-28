$script = <<-SCRIPT
cat /configs/id_bionic.pub >> .ssh/authorized_keys
SCRIPT

$scriptChavePUB = <<-SCRIPT
cat /vagrant/configs/id_bionic.pub >> .ssh/authorized_keys
SCRIPT

$scriptChavePRI = <<-SCRIPT
cp /vagrant/id_bionic  /home/vagrant
chmod 400 /home/vagrant/id_bionic
chown vagrant:vagrant /home/vagrant/id_bionic
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

$installAnsible = <<-SCRIPT
    apt-get update
    apt-get install -y software-properties-common
    apt-get-add-repository --yes --update ppa:ansible/ansible
    apt-get install -y ansible
SCRIPT

$AnsibleBook = <<-SCRIPT
ansible-playbook -i /vagrant/configs/ansible/hosts /vagrant/configs/ansible/playbook.yml
SCRIPT

Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/bionic64"
  config.vm.provider "virtualbox" do |v|
    v.memory = 512
    v.cpus = 1
  end
  # config.vm.define "mysqldb" do |mysql|
  #   mysql.vm.network "private_network", ip: "192.168.40.4"
  #   #mysql.vm.network "private_network", type: "dhcp"
  #   #mysql.vm.network "public_network", ip: "192.168.0.145"
  #   #mysql.vm.provision "shell", inline: "echo Hello, World >> hello.txt"
  #   #Você pode criar functions(provisioners) para chamar nos provisioners, veja
  #   mysql.vm.provision "shell", inline: $script
  #   mysql.vm.provision "shell", inline: $scriptMysql
  #   mysql.vm.provision "shell", inline: $scriptChangeBindIpDefaultMysql
  #   mysql.vm.synced_folder "./configs", "/configs"
  #   mysql.vm.synced_folder ".", "/vagrant", disabled: true  
  # end

  config.vm.define "phpweb" do |phpweb|
    phpweb.vm.network "forwarded_port", guest: 8888, host: 8888
    phpweb.vm.network "public_network", bridge: "en0: Wi-Fi (Wireless)", ip: "192.168.40.55"

    phpweb.vm.provision "shell", inline: $installPuppet

    #Depois de instalar o puppet, vamos instalar os itens provisionados no arquivo phpweb.pp em configs/manifests
    phpweb.vm.provision "puppet" do |puppet|
        puppet.manifests_path = "./configs/manifests"
        puppet.manifest_file = "phpweb.pp"
    end

  end

  config.vm.define "mysqlserver" do |mysqlserver|
    mysqlserver.vm.network "public_network", bridge: "en0: Wi-Fi (Wireless)", ip: "192.168.40.56"

    mysqlserver.vm.provision "shell", inline: $scriptChavePUB
  end

  config.vm.define "ansible" do |ansible|
    ansible.vm.network "public_network", bridge: "en0: Wi-Fi (Wireless)", ip: "192.168.40.57" 
    ansible.vm.provision "shell", inline: $installAnsible
    ansible.vm.provision "shell", inline: $scriptChavePRI
    ansible.vm.provision "shell", inline: $AnsibleBook
  end

  
end

