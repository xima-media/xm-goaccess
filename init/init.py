#!./venv/bin/python3
# -*- coding: utf-8 -*-

import os
import sys
import subprocess

# Check Python version
assert sys.version_info >= (3, 6), f'[ERROR] This script needs minimum Python 3.6!'

try:
    from jinja2 import Template
except ImportError:
    print('This program requires Jinja2 (http://jinja.pocoo.org/)! Installation with pip: pip3 install Jinja2')
    sys.exit(1)

try:
    import yaml
except ImportError:
    print('This program requires PyYAML (https://pyyaml.org/)! Installation with pip: pip3 install PyYAML')
    sys.exit(1)


class InitProject:
    configuration = []
    configuration_file = ''
    answers = {}

    def __init__(self):
        """
        Initialization
        :return:
        """
        print()
        print(f'{CliTextColor.GREEN}**************************************************************{CliTextColor.ENDC}')
        print(f'{CliTextColor.GREEN}*                   Project Initialization                   *{CliTextColor.ENDC}')
        print(f'{CliTextColor.GREEN}**************************************************************{CliTextColor.ENDC}')
        print('Website: https://git.xima.de/opensource/webs-typo3-project-template-v10')
        print()
        self.load_configuration()
        self.gather_answers()
        self.substitute_placeholder()
        self.cleanup()
        self.init_git()
        print()
        print(f'{CliTextColor.GREEN}Done.{CliTextColor.ENDC}')
        print()

    def load_configuration(self):
        """
        Loads configuration
        :return:  
        """
        self.configuration_file = os.path.dirname(os.path.realpath(__file__)) + '/config.yaml'
        print(f'{Subject.INFO} Load configuration file "{self.configuration_file}"!')
        if os.path.isfile(self.configuration_file) is False:
            print(f'{Subject.ERROR} Missing configuration file "{self.configuration_file}"!')
            print()
            sys.exit(1)
        self.configuration = yaml.safe_load(open(self.configuration_file))

    def gather_answers(self):
        """
        Gather answers for configured questions
        :return:
        """
        if self.configuration['questions'] is None:
            print(f'{Subject.WARNING} No questions found in {self.configuration_file}!')
            return

        for key, question in self.configuration['questions'].items():
            self.answers[key] = ''
            while self.answers[key] is '':
                self.answers[key] = self.get_input(question)

    def substitute_placeholder(self):
        """
        Substitutes all placeholder in given file
        :return:
        """
        if not ('substitutions' in self.configuration) or self.configuration['substitutions'] is None:
            print(f'{Subject.WARNING} No substitutes found in {self.configuration_file}!')
            return

        for file in self.configuration['substitutions']:
            try:
                with open(file) as f:
                    template = Template(f.read())
                print(f'{Subject.INFO} Substitute placeholder in "{file}"')
                file = open(file, 'w')
                file.write(template.render(self.answers))
                file.close()
            except FileNotFoundError:
                print(f'{Subject.ERROR} File "{file}" not found!')
            except PermissionError:
                print(f'{Subject.ERROR} You are not allowed to read  "{file}"!')

    @staticmethod
    def get_input(question):
        """
        Returns user input
        :param question: String
        :return:
        """
        return input(f'{Subject.CONFIG} {question}: ')

    @staticmethod
    def cleanup():
        """
        Removes initialization scripts
        :return:
        """
        print(f'{Subject.INFO} Remove initialization scripts')
        subprocess.call(f'rm -rf ./init', shell=True)
        subprocess.call(f'rm -rf ./venv', shell=True)

    def init_git(self):
        """
        Initializes git repository
        :return:
        """
        if not('GIT_REPO_URL' in self.answers): 
            print(f'{Subject.WARNING} No git repository initialized because GIT_REPO_URL does not exists.')
            return
        
        git_repo = self.answers['GIT_REPO_URL']
        print(f'{Subject.INFO} Remove .git-Directory if exists')
        subprocess.call('rm -rf .git', shell=True)
        print(f'{Subject.INFO} Initialize git repository')
        subprocess.call('git init', shell=True)
        print(f'{Subject.INFO} Set git origin {git_repo}')
        subprocess.call(f'git remote add origin {git_repo}', shell=True)

        print(f'{Subject.INFO} Add and commit changes')
        subprocess.call(f'git add .', shell=True)
        subprocess.call(f'git commit -m "[Task] Initial commit"', shell=True)
        git_push = self.get_input('Push changes to origin master? (y|n)[n]')

        if git_push == 'y':
            subprocess.call(f'git push -u origin master', shell=True)


class CliTextColor:
    """
    Provides text colors for command line.
    """
    BEIGE = '\033[96m'
    PURPLE = '\033[95m'
    BLUE = '\033[94m'
    YELLOW = '\033[93m'
    GREEN = '\033[92m'
    RED = '\033[91m'
    BLACK = '\033[90m'
    ENDC = '\033[0m'
    BOLD = '\033[1m'
    UNDERLINE = '\033[4m'


class Subject:
    """
    Provides subjects for output in command line.
    """
    TEST = f'{CliTextColor.BEIGE}[TEST]{CliTextColor.ENDC}'
    INFO = f'{CliTextColor.BLUE}[INFO]{CliTextColor.ENDC}'
    CONFIG = f'{CliTextColor.YELLOW}[CONFIG]{CliTextColor.ENDC}'
    OK = f'{CliTextColor.GREEN}[OK]{CliTextColor.ENDC}'
    WARNING = f'{CliTextColor.YELLOW}[WARNING]{CliTextColor.ENDC}'
    ERROR = f'{CliTextColor.RED}[ERROR]{CliTextColor.ENDC}'


if __name__ == "__main__":
    InitProject()
