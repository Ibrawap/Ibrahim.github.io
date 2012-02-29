from fabric.api import *
from fabric.contrib import files
from fabric.contrib.console import confirm
from time import strftime

env.deploy_message_min_length = 12
env.apps_dir = '/export/apps'
env.deploy_user = 'igndeploy'
env.deploy_group = 'igndeploy'

@hosts('media-oyster-prd-admin-01.las1.colo.ignops.com')
def verify_access():
    "Verify you have the necessary access and permissions to deploy"

    # If we run a command with sudo as the deploy user we know enough
    with hide('running', 'stdout'):
        sudo('hostname', True, env.deploy_user)

def bootstrap():
    "Bootstrap a new code.ign.com server by cloning Brood and code.ign.com repositories"

    if not files.exists(env.apps_dir, True):
        sudo('install -m 755 -o %s -g %s -d %s' % (env.deploy_user, env.deploy_group, env.apps_dir))

    if not files.exists(env.apps_dir + '/brood', True):
        with cd(env.apps_dir):
            sudo('git clone git@github.com:ign/brood.git', True, env.deploy_user)

    if not files.exists(env.apps_dir + '/code.ign.com', True):
        with cd(env.apps_dir):
            sudo('git clone --recursive git://media-oyster-prd-admin-01.las1.colo.ignops.com/code.ign.com', True, env.deploy_user)
            sudo('chmod 777 ' + env.apps_dir + '/code.ign.com/data/cache')

@hosts('media-oyster-prd-admin-01.las1.colo.ignops.com')
def deploy(app_ref='', static_ref=''):
    "Deploy code.ign.com with Brood"

    def verify_master(repo):
        with hide('running'):
            # Try to see if we pushed; only works if we're in the directory of the git repo we're deploying to
            origin_url = local('git config --get remote.origin.url')
            if origin_url == repo:
                local_dev = local('git rev-parse dev')
                remote_dev = local('git ls-remote %s master dev' % repo)[0:40]
                if (local_dev != remote_dev):
                    if not confirm("dev branch in local repository does not match dev branch in remote repository -- you may need to 'git push'. Deploy anyway?", False):
                        abort("dev branch in local repository does not match dev branch in remote repository -- you may need to 'git push'")

            checksums = local('git ls-remote %s master dev' % repo).split("\n")
            if checksums[0][0:40] != checksums[1][0:40]:
                if not confirm("master branch does not match dev branch. Deploy anyway?", False):
                    abort("master branch does not match dev branch")

    # verify_master('git@github.com:ign/code.ign.com.git')

    # Enforce minimum deploy message length to prevent people from accidentally e-mailing out their password
    deploy_message = ''
    while len(deploy_message) < env.deploy_message_min_length:
        if deploy_message != '':
            print "Deploy message must be at least %d characters long" % (env.deploy_message_min_length)
        deploy_message = prompt('Deploy message:')

    # Ensure only one deploy runs at a time
    lock_file = '/tmp/brood.lock'
    with settings(
        hide('warnings', 'running', 'stdout', 'stderr'),
        warn_only=True
    ):
        if sudo('lockfile -r0 %s' % lock_file).failed:
            abort("Another deploy is currently running")

    with cd(env.apps_dir + '/brood'):
        # Update brood before executing overlord script
        sudo('git pull --ff-only', True, env.deploy_user)
        sudo('git clean -dxf', True, env.deploy_user)

        sudo('mkdir -p /logs/deploys')

        def quote(string):
            return "'" + string.replace("'", "'\"'\"'") + "'"

        # Deploy app
        log_file = '/logs/deploys/deploy_%s_app_%s.log' % (strftime('%Y%m%d-%H%M%S'), env.user)
        values = (quote(deploy_message), quote(app_ref), quote(env.user), quote(log_file))
        sudo('/usr/local/php/bin/php bin/overlord.php -c ../code.ign.com/brood.xml -l DEBUG -m %s -r %s -u %s | tee %s | grep --line-buffered -v \' \(\*\*\|II\) \'' % values)
        sudo('bzip2 %s' % log_file)

    with settings(
        hide('warnings', 'running', 'stdout', 'stderr'),
        warn_only=True
    ):
        sudo('rm -f %s' % lock_file)

def invoke(command, useSudo=False):
    "Run arbitrary command on all hosts"

    if useSudo:
        sudo(command)
    else:
        run(command)
