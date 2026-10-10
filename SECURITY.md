# Security Policy

This policy describes how reported security vulnerabilities will be handled.

## Preface

Security vulnerabilities describe issues that allow attackers to compromise your workspace. Such potentially dangerous issues must be confidently disclosed via GitHub Private Vulnerability Reporting. Vulnerabilities will be addressed in a manner of time depending on their severity. Also, depending on the severity we will decide whether to release a hotfix, or include a fix within the next scheduled version.

## Guidelines

We enforce the following rules within our security policy:

- Security vulnerabilities must be reported confidently
- Describe the issue as precisely as possible
- Include a step-by-step list that shows how to reproduce the issue
- Do not attach files if not absolutely necessary
- Long walls of texts can be dismissed as unnecessary noise
- Multiple issues must be reported in separate submissions
- AI slop reports will be closed and dismissed
- You will be given credit if your report is valid and accepted
- We will credit you by only mentioning your GitHub handle
- There is no warranty that your report will be accepted

# Severity tiers

We have our own measurements of severity that affect how fast we respond to an issue as well as how fast we publish a fix.

1. High severity

High severity vulnerabilities are defined in a way that third-party entities are able to compromise a workspace without any privilegues. These exploits typically don't require any social engineering or prior action by authenticated users, administrators, or server owners. Hence those vulnerabilities are eligible for publishing a hotflix outside of the usual schedule. We strive to release such a hotfix as fast as possible.

2. Medium severity

Medium severity vulnerabilities are defined in a way that users without administrative rights are able to compromise a workspace, or parts of a workspace, for example, by doing malicious actions such as injecting javascript code into parts of the app that are not designed to do such things. While we encourage workspace owners to only onboard users that are trustworthy, we go by the motto _better safe than sorry_. Hence we will provide a fix on a scheduled version, or as hotfix, depending on the actual impact.

3. Low severity

Low severity vulnerabilities are defined in a way the harm they may cause is either low, or unlikely to ever happen, or can only be abused by privilegued users (administrators). These vulnerabilities will be fixed in one of the next scheduled versions, and are not eligible for a quick hotfix.