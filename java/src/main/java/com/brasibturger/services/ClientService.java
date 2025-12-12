package com.brasibturger.services;

import com.brasibturger.dtos.ClientDTO;
import com.brasibturger.models.Client;
import com.brasibturger.repositories.ClientRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import java.util.List;
import java.util.stream.Collectors;

@Service
@RequiredArgsConstructor
@Transactional
public class ClientService {
    
    private final ClientRepository clientRepository;
    
    public List<ClientDTO> getAllClients() {
        return clientRepository.findAll()
                .stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }
    
    public ClientDTO getClientById(Long id) {
        return clientRepository.findById(id)
                .map(this::convertToDTO)
                .orElseThrow(() -> new RuntimeException("Client non trouvé"));
    }
    
    public ClientDTO createClient(ClientDTO clientDTO) {
        if (clientRepository.existsByEmail(clientDTO.getEmail())) {
            throw new RuntimeException("Email déjà utilisé");
        }
        
        Client client = convertToEntity(clientDTO);
        client.setStatut(Client.Statut.ACTIF);
        Client saved = clientRepository.save(client);
        return convertToDTO(saved);
    }
    
    public ClientDTO updateClient(Long id, ClientDTO clientDTO) {
        Client client = clientRepository.findById(id)
                .orElseThrow(() -> new RuntimeException("Client non trouvé"));
        
        client.setNom(clientDTO.getNom());
        client.setPrenom(clientDTO.getPrenom());
        client.setTelephone(clientDTO.getTelephone());
        client.setAdresse(clientDTO.getAdresse());
        
        Client updated = clientRepository.save(client);
        return convertToDTO(updated);
    }
    
    public void deleteClient(Long id) {
        clientRepository.deleteById(id);
    }
    
    private ClientDTO convertToDTO(Client client) {
        return ClientDTO.builder()
                .idClient(client.getIdClient())
                .nom(client.getNom())
                .prenom(client.getPrenom())
                .email(client.getEmail())
                .telephone(client.getTelephone())
                .adresse(client.getAdresse())
                .statut(client.getStatut().toString())
                .build();
    }
    
    private Client convertToEntity(ClientDTO dto) {
        return Client.builder()
                .nom(dto.getNom())
                .prenom(dto.getPrenom())
                .email(dto.getEmail())
                .telephone(dto.getTelephone())
                .adresse(dto.getAdresse())
                .build();
    }
}
