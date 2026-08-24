<?php

declare(strict_types=1);

namespace Romainsickenberg\ProgrammaticResume\Generator;

use JustSteveKing\Resume\Builders\ResumeBuilder;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Skill;
use JustSteveKing\Resume\DataObjects\Work;
use JustSteveKing\Resume\Enums\SkillLevel;
use JustSteveKing\Resume\ValueObjects\Email;
use JustSteveKing\Resume\ValueObjects\Url;

final class SupportN1N2 extends BaseResume
{
    #[\Override]
    public function basics(): Basics
    {
        return new Basics(
            name: self::FULL_NAME,
            label: $this->trans('basics.support_position'),
            email: new Email(self::EMAIL),
            phone: self::PHONE,
            url: new Url(self::URL),
            summary: $this->getSummary(),
            location: $this->getLocation(),
            profiles: $this->getRelatedProfiles(),
        );
    }

    /**
     * None of the individual IT-sector jobs (backend/full-stack development)
     * are directly relevant to an IT Support N1/N2 role, and the Security
     * sector (Securitas) isn't either. Rather than listing them with
     * dev-specific highlights, fold them into a single placeholder entry
     * that acknowledges the software engineering background exists without
     * claiming support-specific relevance for it.
     */
    public function addWorks(ResumeBuilder $builder): ResumeBuilder
    {
        $itExperiences = $this->getAllWorkExperiences()->get(WorkTypes::IT->value);

        $builder->addWork(new Work(
            name: $this->trans('work.software_engineering_summary.name'),
            position: $this->trans('work.software_engineering_summary.position'),
            startDate: min(array_map(static fn(Work $w) => $w->startDate, $itExperiences)),
            endDate: max(array_map(static fn(Work $w) => $w->endDate, $itExperiences)),
            summary: $this->trans('work.software_engineering_summary.summary'),
        ));

        return $builder;
    }

    public function addSkills(ResumeBuilder $builder): ResumeBuilder
    {
        $builder
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.support_os_name'),
                    level: SkillLevel::Advanced,
                    keywords: [
                        'Windows 10 / 11',
                        'Windows Server',
                        'macOS',
                        $this->trans('skills.support_os_linux_basics'),
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.support_directory_name'),
                    level: SkillLevel::Advanced,
                    keywords: [
                        'Active Directory',
                        'Microsoft Entra ID (Azure AD)',
                        'Group Policy (GPO)',
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.support_ticketing_name'),
                    level: SkillLevel::Advanced,
                    keywords: [
                        'Jira Service Management',
                        $this->trans('skills.support_ticketing_itil'),
                        $this->trans('skills.support_ticketing_incident_mgmt'),
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.support_networking_name'),
                    level: SkillLevel::Advanced,
                    keywords: [
                        'TCP/IP',
                        'DNS & DHCP',
                        'VPN',
                        $this->trans('skills.support_networking_wifi_troubleshooting'),
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.support_hardware_name'),
                    level: SkillLevel::Advanced,
                    keywords: [
                        $this->trans('skills.support_hardware_workstation_troubleshooting'),
                        'Mobile Device Management (MDM)',
                        $this->trans('skills.support_hardware_printer_support'),
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.support_collaboration_name'),
                    level: SkillLevel::Advanced,
                    keywords: [
                        'Microsoft 365 (Outlook, Teams, SharePoint)',
                        $this->trans('skills.support_collaboration_remote_tools'),
                    ]
                )
            );

        return $builder;
    }

    protected function getSummary(): string
    {
        return implode("\n\n", [
            $this->trans('basics.support_summary_role'),
            $this->trans('basics.support_summary_experience'),
            $this->trans('basics.summary_authorization'),
        ]);
    }
}
