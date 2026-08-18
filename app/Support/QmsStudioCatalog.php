<?php

namespace App\Support;

use App\Models\QmsDataSource;
use Illuminate\Support\Collection;

class QmsStudioCatalog
{
    public function formStudio(): array
    {
        return [
            'componentGroups' => [
                'Basic' => [
                    $this->field('text', 'Text', 'Text', 'Event title'),
                    $this->field('textarea', 'Textarea', 'Textarea', 'Narrative'),
                    $this->field('number', 'Number', 'Number', 'Quantity'),
                    $this->field('email', 'Email', 'Email', 'Contact email'),
                    $this->field('toggle', 'Toggle', 'Boolean', 'Confidential'),
                ],
                'Date/time' => [
                    $this->field('date', 'Date', 'Date', 'Event date'),
                    $this->field('time', 'Time', 'Time', 'Time of occurrence'),
                    $this->field('duration', 'Duration', 'Duration', 'Downtime'),
                ],
                'Choice' => [
                    $this->field('dropdown', 'Dropdown', 'Choice', 'Severity'),
                    $this->field('searchable_dropdown', 'Searchable dropdown', 'Choice', 'Location'),
                    $this->field('multi_select', 'Multi-select', 'Choice', 'Event categories'),
                    $this->field('checkbox', 'Checkbox', 'Boolean', 'Mandatory report'),
                ],
                'Directory' => [
                    $this->field('user', 'User', 'Directory', 'Reported by', 'DS-USERS-LOCAL'),
                    $this->field('manager', 'Manager', 'Directory', 'Manager', 'DS-ENTRA-USERS'),
                    $this->field('department', 'Department', 'Directory', 'Department'),
                    $this->field('location', 'Location', 'Directory', 'Location', 'DS-USERS-LOCAL'),
                ],
                'Aviation' => [
                    $this->field('aircraft_type', 'Aircraft type', 'Aviation', 'Aircraft type', 'DS-FLEET-REGISTRY'),
                    $this->field('registration', 'Registration', 'Aviation', 'Aircraft registration', 'DS-FLEET-REGISTRY'),
                    $this->field('flight_number', 'Flight number', 'Aviation', 'Flight number'),
                    $this->field('route', 'Route', 'Aviation', 'Route'),
                ],
                'QMS' => [
                    $this->field('risk', 'Risk', 'QMS', 'Risk rating'),
                    $this->field('incident', 'Incident', 'QMS', 'Related incident'),
                    $this->field('capa', 'CAPA', 'QMS', 'Related action'),
                    $this->field('supplier', 'Supplier', 'QMS', 'Supplier', 'DS-SUPPLIERS'),
                ],
                'Advanced' => [
                    $this->field('file', 'File', 'Files', 'Evidence'),
                    $this->field('signature', 'Signature', 'Advanced', 'Acknowledgement'),
                    $this->field('calculated', 'Calculated', 'Advanced', 'Calculated risk score'),
                    $this->field('repeatable', 'Repeatable section', 'Layout', 'People involved'),
                ],
            ],
            'previewModes' => ['Desktop', 'Tablet', 'Mobile'],
        ];
    }

    public function workflowStudio(): array
    {
        return [
            'nodeGroups' => [
                'Flow' => [
                    $this->node('start', 'Start', 'Start event'),
                    $this->node('end', 'End', 'End event'),
                    $this->node('decision', 'Decision', 'Branch'),
                    $this->node('parallel', 'Parallel branch', 'Parallel work'),
                    $this->node('join', 'Join', 'Synchronization'),
                ],
                'Work' => [
                    $this->node('human_task', 'Human task', 'Task'),
                    $this->node('approval', 'Approval', 'Approval'),
                    $this->node('investigation', 'Investigation', 'Workspace'),
                    $this->node('action', 'Action', 'CAPA'),
                ],
                'Automation' => [
                    $this->node('notification', 'Notification', 'Message'),
                    $this->node('timer', 'Timer / SLA', 'SLA'),
                    $this->node('escalation', 'Escalation', 'Rule'),
                    $this->node('webhook', 'Webhook', 'Integration'),
                ],
            ],
            'defaultStages' => ['Draft', 'Submitted', 'Screening', 'Investigation', 'CAPA', 'Verification', 'Closed'],
        ];
    }

    public function dataSourceOptions(): Collection
    {
        return QmsDataSource::orderBy('name')
            ->get(['code', 'name', 'source_type', 'entity', 'permission_scope', 'status']);
    }

    private function field(string $type, string $label, string $category, string $defaultLabel, ?string $dataSource = null): array
    {
        return [
            'type' => $type,
            'label' => $label,
            'category' => $category,
            'default_label' => $defaultLabel,
            'data_source' => $dataSource,
        ];
    }

    private function node(string $type, string $label, string $kind): array
    {
        return [
            'type' => $type,
            'label' => $label,
            'kind' => $kind,
        ];
    }
}
