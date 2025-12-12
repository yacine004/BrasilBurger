package com.brasibturger.services;

import com.brasibturger.dtos.GestionnaireDTO;
import com.brasibturger.exceptions.ApiException;
import com.brasibturger.models.Gestionnaire;
import com.brasibturger.repositories.GestionnaireRepository;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.stream.Collectors;

@Service
@RequiredArgsConstructor
@Transactional
@Slf4j
public class GestionnaireService {
    
    private final GestionnaireRepository gestionnaireRepository;
    private final PasswordEncoder passwordEncoder;
    
    public List<GestionnaireDTO> getAllGestionnaires() {
        log.info("Retrieving all gestionnaires");
        return gestionnaireRepository.findAll().stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }
    
    public List<GestionnaireDTO> getActiveGestionnaires() {
        log.info("Retrieving active gestionnaires");
        return gestionnaireRepository.findAll().stream()
                .filter(g -> "ACTIF".equals(g.getStatut()))
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }
    
    public GestionnaireDTO getGestionnaireById(Long id) {
        log.info("Retrieving gestionnaire with id: {}", id);
        Gestionnaire gestionnaire = gestionnaireRepository.findById(id)
                .orElseThrow(() -> new ApiException("Gestionnaire not found with id: " + id, 404));
        return convertToDTO(gestionnaire);
    }
    
    public GestionnaireDTO getGestionnaireByEmail(String email) {
        log.info("Retrieving gestionnaire by email: {}", email);
        Gestionnaire gestionnaire = gestionnaireRepository.findByEmail(email)
                .orElseThrow(() -> new ApiException("Gestionnaire not found with email: " + email, 404));
        return convertToDTO(gestionnaire);
    }
    
    public GestionnaireDTO createGestionnaire(GestionnaireDTO gestionnaireDTO) {
        log.info("Creating new gestionnaire: {}", gestionnaireDTO.getEmail());
        
        if (gestionnaireRepository.existsByEmail(gestionnaireDTO.getEmail())) {
            throw new ApiException("Email already exists: " + gestionnaireDTO.getEmail(), 400);
        }
        
        Gestionnaire gestionnaire = convertToEntity(gestionnaireDTO);
        gestionnaire.setMotDePasse(passwordEncoder.encode(gestionnaireDTO.getMotDePasse()));
        gestionnaire.setStatut(Gestionnaire.Statut.ACTIF);
        
        Gestionnaire saved = gestionnaireRepository.save(gestionnaire);
        return convertToDTO(saved);
    }
    
    public GestionnaireDTO updateGestionnaire(Long id, GestionnaireDTO gestionnaireDTO) {
        log.info("Updating gestionnaire with id: {}", id);
        Gestionnaire gestionnaire = gestionnaireRepository.findById(id)
                .orElseThrow(() -> new ApiException("Gestionnaire not found with id: " + id, 404));
        
        gestionnaire.setNom(gestionnaireDTO.getNom());
        gestionnaire.setPrenom(gestionnaireDTO.getPrenom());
        gestionnaire.setTelephone(gestionnaireDTO.getTelephone());
        if (gestionnaireDTO.getStatut() != null) {
            gestionnaire.setStatut(Gestionnaire.Statut.valueOf(gestionnaireDTO.getStatut()));
        }
        
        if (gestionnaireDTO.getMotDePasse() != null && !gestionnaireDTO.getMotDePasse().isEmpty()) {
            gestionnaire.setMotDePasse(passwordEncoder.encode(gestionnaireDTO.getMotDePasse()));
        }
        
        Gestionnaire updated = gestionnaireRepository.save(gestionnaire);
        return convertToDTO(updated);
    }
    
    public void deleteGestionnaire(Long id) {
        log.info("Deleting gestionnaire with id: {}", id);
        if (!gestionnaireRepository.existsById(id)) {
            throw new ApiException("Gestionnaire not found with id: " + id, 404);
        }
        gestionnaireRepository.deleteById(id);
    }
    
    private GestionnaireDTO convertToDTO(Gestionnaire gestionnaire) {
        return GestionnaireDTO.builder()
                .nom(gestionnaire.getNom())
                .prenom(gestionnaire.getPrenom())
                .email(gestionnaire.getEmail())
                .telephone(gestionnaire.getTelephone())
                .statut(gestionnaire.getStatut() != null ? gestionnaire.getStatut().toString() : null)
                .build();
    }
    
    private Gestionnaire convertToEntity(GestionnaireDTO gestionnaireDTO) {
        return Gestionnaire.builder()
                .nom(gestionnaireDTO.getNom())
                .prenom(gestionnaireDTO.getPrenom())
                .email(gestionnaireDTO.getEmail())
                .telephone(gestionnaireDTO.getTelephone())
                .motDePasse(gestionnaireDTO.getMotDePasse())
                .build();
    }
}
